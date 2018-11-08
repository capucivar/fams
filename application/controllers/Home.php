<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");

class Home extends BaseC {

    public function __construct() {
        parent::__construct(); 
        $this->load->model("UserModel"); 
    }

    public function index(){
        $data["agent"]        = $this->agent;  
        $data["menuCatagery"] = "主页";
        $data["menuSub"]      = "首页";
        $data["menuDetail"]   = "";
        $data["notice"] = "";
        $data["mCount"] = "";//下级数量
        $data["earnings"] = "0";
        $notice = $this->NoticeModel->getNoticeNew();//获取最新一条公告内容
        if($notice!=""){
            $data["notice"] = $notice["CONTENT"];
        }
        if($this->agent["LEVEL"]==SysDict::$ANGENTLEVEL["one"]){
            //一级代理商查询下级代理商
            $data["mCount"] = $this->AgentModel->getLowerAgentListDataCount($this->agent["AID"],"");
        }else if($this->agent["LEVEL"]==SysDict::$ANGENTLEVEL["two"]){
            //二级代理商查询绑定的玩家数量
            $data["mCount"] = $this->UserModel->getUserListDataCount($this->agent["AID"],"");
            //代开房卡收益
            $data["earnings"] = "123";
        }
        $this->load->view('common/header', $data);
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('home');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function delCookie() {
        delete_cookie(SysDict::$AUTH_COOKIE_NAME);
    }

    public function login() {
        $this->load->view('login');
    }
    public function flogin() {  
        $isfirst = $this->agent["ISFIRST"]==1?true:false; 
        if($isfirst){
            $data["aid"] = $this->agent["AID"];
            $data["cell"] = $this->agent["CELLPHONE"];
//            delete_cookie(SysDict::$AUTH_COOKIE_NAME);
            $this->load->view('loginfirst',$data);
            $this->load->view('common/modalinfo'); 
        }else{
            echo "<script>window.location.href='/home/account';</script>";
        } 
    }

    public function signout() {
        delete_cookie(SysDict::$AUTH_COOKIE_NAME);
        $this->login(); 
    }
 
     public function account() {
        $data["agent"]        = $this->agent;  
        $data["menuCatagery"] = "主页";
        $data["menuSub"]      = "账户";
        $data["menuDetail"]   = "账户概览";  
        $data["user"] = $this->user;
        $this->load->view('common/header', $data);
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('account');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    } 
    public function invalid() { 
        // 如果当前账户未冻结，则跳转到 Dashboard
//        if ($this->agent["STATE"] != SysDict::$AGENTSTATE["invalid"]) {
//            echo "<script type='text/javascript'>window.location.href='/user/account';</script>";
//            return;
//        }
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "主页";
        $data["menuSub"]      = "账户冻结";
        $data["menuDetail"]   = "信息概览";

        $this->load->view('common/header', $data);
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('invalid');
        $this->load->view('common/footer');
    }

    public function doLogin() {
        $aid = $_REQUEST["userName"];
        $pwd      = $_REQUEST["pwd"];
        $rmm      = $_REQUEST["rmm"];
        
        if (!$this->AgentModel->isLoginValid($aid, $pwd)) parent::echoFail("验证失败");

        // 获取登录token
        $token = $this->AgentModel->getToken($aid);
        if (empty($token)) parent::echoFail("获取 Token 失败");

        // 记住我，记三天；否则，记一天
        $remDays = 1;
        if ($rmm == "true") {
            $remDays = 3;
        }
        set_cookie(SysDict::$AUTH_COOKIE_NAME, $token, $remDays * 24 * 60 * 60);
        $this->AgentModel->updateLastLogin($aid);
        //记录日志
        $data["LTYPE"]= 10;
        $data["AID"]= $aid;
        $data["CONTENT"]= "登录成功";
        $this->LogModel->saveLog($data);
        parent::echoSuccess("登录成功");
        
    } 
    public function doChangepwd(){ 
        $aid = parent::getParam("AID");
        if(empty($aid)) parent::echoFail("AID is null");
        $newPwd = parent::getParam("NEWPWD");
        $vcode = parent::getParam("VCODE");
        $cellphone =  parent::getParam("CELLPHONE");
        //检查手机号码是否为预留手机号码
        $cellisok = $this->AgentModel->isMobileOK($cellphone,$aid);
        if(!$cellisok){
            parent::echoFail("手机号码与系统预留手机号不一致");
        }
        if($this->ChangePwdModel->checkVCode($cellphone,$vcode)){//验证码验证
             if($this->ChangePwdModel->changePwd($aid,$newPwd)){//修改密码 
                 //修改手机号
                $this->AgentModel->updatePnum($cellphone,$aid);
                $this->ChangePwdModel->updateState($cellphone);
                $data["LTYPE"]= 11;
                $data["AID"]= $aid;
                $data["CONTENT"]= "首次登陆修改密码";
                $this->LogModel->saveLog($data); 
                //向agent_player中添加一条数据
                $GID = $this->agent["GAMEID"];
                $this->saveUser($GID); 
                parent::echoSuccess("密码修改成功");
            }else
                parent::echoFail("密码修改失败");
         }else 
             parent::echoFail("手机验证码验证失败");
    }
    public function saveUser($gid){
        $agent = $this->agent; 
        $param["GID"] = $gid;
        $param["UNO"] = $agent["UNO"];
        $param["WXID"] = $agent["WXID"];
        $param["CELLPHONE"] = $agent["CELLPHONE"];
        $param["REMARK"] = "";
            
        $user = $this->UserModel->getUserByGid($gid);
        $result = 0;
        if($user!=""){
            //修改 
            $param["PID"] = $user["PID"];
            $result = $this->UserModel->updateUserInfo($param);
        }else{
            //添加 
            $param["NICKNAME"] = $agent["ANAME"]; 
            $param["PAID"] = $agent["AID"];
            $result = $this->UserModel->saveUserInfo($param);
        }
        return $result;
    }
    public function test(){
//        $url = "http://api.dandanq.cn/getrange/"; 
//        $post_data = array ("cuuid" =>"421124964","scode"=>"5e35f72e8ebabe515db5fc1ccdaff6d2","syscode"=>"03f37779cd022aa06f87eb92ad38ae3f");
        
        $url = "http://pro.dandanq.cn/createdealer"; 
        $post_data = array ("phone" =>"18610022014","name"=>"关关","pwd"=>"123456");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        print_r($output); 
         //-----------------------------
        
//        echo strlen("12");
    }
}
