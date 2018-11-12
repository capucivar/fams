<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");

class LoginC extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("LoginModel"); 
    }  
    public function delCookie() {
        delete_cookie(SysDict::$AUTH_COOKIE_NAME);
    }

    public function login() { 
        $this->load->view('login');
    } 

    public function signout() {
        delete_cookie(SysDict::$AUTH_COOKIE_NAME);
        $this->login(); 
    } 
    public function invalid() { 
        // 如果当前账户未冻结，则跳转到 Dashboard
//        if ($this->agent["STATE"] != SysDict::$AGENTSTATE["invalid"]) {
//            echo "<script type='text/javascript'>window.location.href='/user/account';</script>";
//            return;
//        }
        $data["baseInfo"]        = $this->baseInfo;
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
        $ucode = $_REQUEST["userName"];
        $pwd      = $_REQUEST["pwd"];
        $rmm      = $_REQUEST["rmm"];   
        if (!$this->LoginModel->isLoginValid($ucode, $pwd)) parent::echoFail("验证失败");
        $user = $this->LoginModel->getUserByCode($ucode);
        if(empty($user)) parent::echoFail("获取 用户信息 失败"); 
        $uid = $user["userid"];
        // 获取登录token
        $token = $this->LoginModel->getToken($uid);
        if (empty($token)) parent::echoFail("获取 Token 失败"); 
        // 记住我，记三天；否则，记一天
        $remDays = 1;
        if ($rmm == "true") {
            $remDays = 3;
        }
        set_cookie(SysDict::$AUTH_COOKIE_NAME, $token, $remDays * 24 * 60 * 60); 
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
                parent::echoSuccess("密码修改成功");
            }else
                parent::echoFail("密码修改失败");
         }else 
             parent::echoFail("手机验证码验证失败");
    } 
}
