<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 
class ChangePwdC extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("ChangePwdModel"); 
        $this->load->model("UserModel"); 
        $this->load->model("LoginModel"); 
    }
    
    public function index() {
        $data["baseInfo"]        = $this->baseInfo;
        $data["menuCatagery"] = "个人设置";
        $data["menuSub"]      = "修改密码";
        $data["menuDetail"]   = ""; 
        $this->load->view('common/header', $data);
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('organization/password');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //发送手机验证码
    public function sendVCode(){
        $cellphone = $this->baseInfo["phone"];
        $validnum = $this->ChangePwdModel->getVcode($cellphone);
        if(!empty($validnum)){
            //发送短信
            $message = "验证码为".$validnum."，请在页面中输入以完成验证";
            $isSMSOk = 1; 
            if ($isSMSOk > 0) {
                parent::echoSuccess($message);
            } else {
                parent::echoFail("发送验证码失败");
            } 
        }else{
            parent::echoFail("短信验证码发送失败");
        }
    } 
    //验证验证码
    public function checkVCode(){
        $cellphone = $this->baseInfo["phone"];
        $vcode = parent::getParam("vcode");
        if($this->ChangePwdModel->checkVCode($cellphone,$vcode)){
            parent::echoSuccess("验证码验证成功");
        }else{
            parent::echoFail("验证码错误");
        }
    }
    //修改密码
    public function changePwd(){ 
        $param = $_REQUEST; 
        $param["userid"] = $this->baseInfo["userid"]; 
        $usercode = $this->baseInfo["usercode"];
        $oldPwd = $param["oldpwd"];
        if($this->LoginModel->isLoginValid($usercode,$oldPwd)){ //旧密码验证
            if($this->ChangePwdModel->checkVCode($param)){//验证码验证
                if($this->ChangePwdModel->changePwd($param)){//修改密码 
                     parent::echoSuccess("密码修改成功");
                }else
                    parent::echoFail("修改密码失败");
            }else
                parent::echoFail("手机验证码验证失败");
        }else
            parent::echoFail("旧密码验证失败");
    }
}
