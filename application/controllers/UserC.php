<?php
/* 
 * 玩家管理，二级代理商的权限
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
class UserC extends BaseC {
    public function __construct() {
        parent::__construct();
        $this->load->model("UserModel");
        $this->load->model("LogModel");
        $this->load->model("MessageModel");
    }
    public function index(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "玩家";
        $data["menuSub"]      = "玩家信息管理";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('user');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function getUserList() { 
        $search = parent::getParam("search"); 
        $aid    = $this->agent["AID"];
        $rows   = $this->UserModel->getUserListData($aid, $search); 
        $count  = $this->UserModel->getUserListDataCount($aid, $search);
        //查询玩家数据
        $post_data = array("uno" =>"");
        $gamerData = parent::HttpPost("User",$post_data); 
        $gamers = json_decode($gamerData,true);
        $result = [];
        if($result!=""){
            foreach ($rows as $row) {
                $gid = $row["GID"];
                $lastLogin = "";
                foreach ($gamers["result"] as $gamer) {
                    if($gid==$gamer["ID"]){
                        $lastLogin = $gamer["LASTLOGIN"]; 
                        break;
                    }
                }
                array_push($result,array_merge($row, array('LASTLOGIN'=>$lastLogin)));
            }
        } 
        parent::echoBootstrapTableData($result, $count);
    }
    
    public function newUser(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "玩家";
        $data["menuSub"]      = "玩家信息管理";
        $data["menuDetail"]   = ""; 
        $data["user"]   = ""; 
        
        $pid = parent::getParam("pid");
        if (!empty($pid)) {
            $paid="";
            if($this->agent["LEVEL"]!=SysDict::$ANGENTLEVEL["admin"] && $this->agent["LEVEL"]!=SysDict::$ANGENTLEVEL["head"])
                $paid = $this->agent["AID"];
            $data["user"] = json_encode($this->UserModel->getUserById($pid, $paid));
        }  
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('usernew');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function saveUser() {
        $param        = $_REQUEST; 
        if(empty($param)){
            parent::echoFail("保存数据失败");
        }
        $aid = $this->agent["AID"];
        $pid = $param["PID"];
         // 验证游戏ID是否已经存在
        if ($this->UserModel->isWXIDExist($param["GID"],$aid,$pid)) {
            parent::echoFail("游戏ID已经存在");
        }
        // 验证微信号是否已经存在
        if ($this->UserModel->isWXIDExist($param["WXID"],$aid,$pid)) {
            parent::echoFail("微信号已经存在");
        }
        // 验证手机号是否已经存在
        if ($this->UserModel->isMobileExist($param["CELLPHONE"],$aid,$pid)) {
            parent::echoFail("手机号已经存在");
        }
        // 保存数据
        if (!empty($pid)) {
            $result = $this->UserModel->updateUserInfo($param);
        } else { 
            $param["PAID"] = $aid;
            $param["NICKNAME"] = ""; 
            $result = $this->UserModel->saveUserInfo($param);
        }
        if ($result) {
            parent::echoSuccess("保存成功");
        } else {
            parent::echoFail("保存数据失败");
        }
    }
    public function delUser(){
        $pid = parent::getParam("pid");
        $paid = $this->agent["AID"];
        $result = $this->UserModel->delUser($pid, $paid);
        if ($result) {
            parent::echoSuccess("删除成功");
        } else {
            parent::echoFail("删除数据失败");
        }
    }
    public function transferRCard(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "玩家绑定";
        $data["menuSub"]      = "转账给玩家";
        $data["menuDetail"]   = ""; 
        
        $data["user"]   = "";  
        $pid = parent::getParam("pid");
        if (!empty($pid)) {
            $data["user"] = json_encode($this->UserModel->getUserById($pid,  $this->agent["AID"]));
        } 
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('usertransferRC');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //玩家代理商转账给玩家
    public function doTransferRCard(){
        $num = (int)parent::getParam("cardnum");
        $paid = $this->agent["AID"]; 
        $level = $this->agent["LEVEL"];
        //检查我的房卡够不够转账的
        if($this->agent["ROOMCARD"]<$num){
            parent::echoFail("抱歉，您的房卡余额不足");
            return;
        }
        $pid = parent::getParam("PID");
        $result = $this->UserModel->transferRCard($num,$pid, $paid,$level+1);
        if ($result) {
            //写入日志
            $logParam["ID"] =  StringUtil::orderid(23, SysDict::$EXUID["transfer_card"]);
            $logParam["OPERATOR"] = $paid; 
            $logParam["TYPE"] = SysDict::$LOGTYPE["rctransfer"];//转账给玩家
            $logParam["NUM"] = 0-$num; 
            $logParam["AID"]=$paid;
            $logParam["TOAID"] = $pid; 
            $logParam["RCID"] = $result;
                    
            $logParam2["ID"] =  StringUtil::orderid(23, SysDict::$EXUID["get_transfer_card"]);
            $logParam2["OPERATOR"] = $paid; 
            $logParam2["TYPE"] = SysDict::$LOGTYPE["get_transfer_card"];//转账给玩家
            $logParam2["NUM"] = $num; 
            $logParam2["AID"]=$pid;
            $logParam2["TOAID"] = $paid; 
            $logParam2["RCID"] = $result;
            
            $this->LogModel->saveRcLog($logParam,$logParam2);
            
            parent::echoSuccess("房卡转账成功");
        } else {
            parent::echoFail("房卡转账失败");
        }
    }
    //申请关闭账号
    public function closeAccount(){ 
        $param["ALTYPE"] = 1;
        $param["ALAID"] = $this->agent["AID"];
        $param["ALPID"] = parent::getParam("pid");
        $param["LEVEL"] = 0;
        $result = $this->MessageModel->saveApplyLog($param);
        if ($result) {
            parent::echoSuccess("申请已提交");
        }else {
            parent::echoFail("申请提交失败");
        }
    }
    //获取游戏玩家列表
    public function getGhostUserlist(){
        $result = $this->UserModel->getGhostUserlist();
        print_r($result[0]);
    }
    //邀请码分享
    public function inviteCode(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "玩家绑定";
        $data["menuSub"]      = "邀请码分享";
        $data["menuDetail"]   = ""; 
        $data["invitecode"] = $this->agent["SHARECODE"];
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('userinvitecode');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
}
