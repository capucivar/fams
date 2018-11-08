<?php
/**
 * 下级代理商
 * **/
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 
class ChangePnumC extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("ChangePwdModel");
        $this->load->model("AgentModel");
        $this->load->model("LogModel");
    }
    public function index() {
        $data["agent"]        = $this->agent;  
        $data["menuCatagery"] = "主页";
        $data["menuSub"]      = "修改手机号";
        $data["menuDetail"]   = "";
        $cellphone = $this->agent["CELLPHONE"]; 
        $data["CELLPHONE"] = $cellphone;
        $data["CELLPHONE2"] = strlen($cellphone)==11?substr($cellphone, 0,3)."****".substr($cellphone, 7):$cellphone;
        
        $this->load->view('common/header', $data);
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('changepnum');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //发送手机验证码
    public function sendVCode(){ 
        $cellphone = parent::getParam("NEWCELLPHONE");
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
        }else{
            parent::echoFail("短信验证码发送失败");
        }
    } 
    //修改手机号
    public function changePnum(){
        $aid = $this->agent["AID"];
        if(empty($aid)){
            parent::echoFail("AID is null");
            return;
        }
        $vcode = parent::getParam("VCODE");
        $newCellphone = parent::getParam("NEWCELLPHONE");
        $cellphone = $this->agent["CELLPHONE"]; 
        
        if($this->AgentModel->isMobileExist($newCellphone,$aid)){//验证新手机号是否存在
            parent::echoFail("新的手机号已经存在"); 
        }
        if($this->ChangePwdModel->checkVCode($newCellphone,$vcode)){//验证码验证 
            if($this->AgentModel->updatePnum($newCellphone,$aid)){//修改手机号
                $this->ChangePwdModel->updateState($newCellphone);
                $data["LTYPE"]= 12;
                $data["AID"]= $aid;
                $data["CONTENT"]= "修改手机号：".$cellphone."->".$newCellphone;
                $this->LogModel->saveLog($data);
                 parent::echoSuccess("手机号修改成功");
            }else
                parent::echoFail("手机号修改失败");
        }else
            parent::echoFail("手机验证码验证失败"); 
    }
}
