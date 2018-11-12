<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 
class ChangePwdC extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("ChangePwdModel");
        $this->load->model("AgentModel");
        $this->load->model("LogModel");
    }
    
    public function index() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "修改密码";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 
        $data["lowerAgent"]       = "";
        
        $cellphone = $this->agent["CELLPHONE"];
        
        $data["CELLPHONE"] = $cellphone;
        $data["CELLPHONE2"] = strlen($cellphone)==11?substr($cellphone, 0,3)."****".substr($cellphone, 7,4):$cellphone;
                
        $this->load->view('common/header', $data);
//        $this->load->view('common/menu');
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('changepwd');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //发送手机验证码
    public function sendVCode(){
        $cellphone = $this->agent["CELLPHONE"]; 
       if(!empty($_REQUEST["phone"])) { 
            $cellphone = $_REQUEST["phone"]; 
        } 
        $validnum = $this->ChangePwdModel->getVcode($cellphone);
        if(!empty($validnum)){
            //发送短信
            $message = "【酒鬼棋牌】验证码为".$validnum."，五分钟内有效，请在页面中输入以完成验证";
            $isSMSOk = RPCUtil::sendSMS($cellphone, $message);
            if ($isSMSOk > 0) {
                parent::echoSuccess("验证码已发送");
            } else {
                parent::echoFail("发送验证码失败");
            }
//            parent::echoSuccess("验证码：".$validnum);
        }else{
            parent::echoFail("短信验证码发送失败");
        }
    } 
    //验证验证码
    public function checkVCode(){
        $cellphone = $this->agent["CELLPHONE"];
        $vcode = parent::getParam("vcode");
        if($this->ChangePwdModel->checkVCode($cellphone,$vcode)){
            parent::echoSuccess("验证码验证成功");
        }else{
            parent::echoFail("验证码错误");
        }
    }
    //修改密码
    public function changePwd(){
        $aid = $this->agent["AID"];
        if(empty($aid)){
            parent::echoFail("AID is null");
            return;
        }
        $oldPwd = parent::getParam("OLDPWD");
        $newPwd = parent::getParam("NEWPWD");
        $vcode = parent::getParam("VCODE");
        $cellphone = $this->agent["CELLPHONE"];
        if($this->AgentModel->isLoginValid($aid,$oldPwd)){ //旧密码验证
            if($this->ChangePwdModel->checkVCode($cellphone,$vcode)){//验证码验证
                if($this->ChangePwdModel->changePwd($aid,$newPwd)){//修改密码
                    $this->ChangePwdModel->updateState($cellphone);
                    $data["LTYPE"]= 11;
                    $data["AID"]= $aid;
                    $data["CONTENT"]= "密码修改成功";
                    $this->LogModel->saveLog($data);
                     parent::echoSuccess("密码修改成功");
                }else
                    parent::echoFail("修改密码失败");
            }else
                parent::echoFail("手机验证码验证失败");
        }else
            parent::echoFail("旧密码验证失败");
    }
}
