<?php
/**
 * 下级代理商
 * **/
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php");
include_once(APP_PATH_L . "phpqrcode.php");

class Agent extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("AgentModel");
        $this->load->model("LogModel");
        $this->load->model("ChangePwdModel");
    }

    public function agentList() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "代理商";
        $data["menuSub"]      = "代理商信息管理";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('agentlist');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }

    public function newAgent() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "代理商";
        $data["menuSub"]      = "新增代理商";
        $data["menuDetail"]   = ""; 
        $data["lowerAgent"]       = "";

        $aid = parent::getParam("aid");
        if (!empty($aid)) {
            $paid="";
            if($this->agent["LEVEL"] != SysDict::$ANGENTLEVEL["admin"] && $this->agent["LEVEL"] != SysDict::$ANGENTLEVEL["head"])
                $paid = $this->agent["AID"];  
            $data["lowerAgent"] = json_encode($this->AgentModel->getAgentById($aid,  $paid));
        }
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('agentnew');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    } 
    
    public function newAgentInfo($uno) {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "代理商";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 
        $data["mAgent"] = "";
        $agent = $this->AgentModel->getAgentByUno($uno);
        if(!empty($agent))
            $data["mAgent"] = json_encode($agent); 
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('agentnewinfo');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    //查找下级代理商数据
    public function getAgentList() { 
        $search = parent::getParam("search"); 
        $aid    = $this->agent["AID"];
        $rows   = $this->AgentModel->getLowerAgentListData($aid, $search);
        $count  = $this->AgentModel->getLowerAgentListDataCount($aid, $search);
        parent::echoBootstrapTableData($rows, $count);
    }

    public function saveAgent() {
        $param        = $_REQUEST; 
        if(empty($param)){
            parent::echoFail("保存数据失败");
        }
        $aid = empty($param["AID"])?"":$param["AID"]; 
        // 验证微信号是否已经存在
        if ($this->AgentModel->isWXIDExist($param["WXID"],$aid)) {
            parent::echoFail("微信ID已经存在");
        }
        // 验证手机号是否已经存在
        if ($this->AgentModel->isMobileExist($param["CELLPHONE"],$aid)) {
            parent::echoFail("手机号已经存在");
        }
        //检查游戏ID是否已经存在
        if ($this->AgentModel->isGIDExist($param["UNO"],$aid)) {
            parent::echoFail("游戏ID已经存在");
        } 
        
        // 保存数据
        if (!empty($param["AID"])) {
            $result = $this->AgentModel->updateMark($param["MARK"],$param["AID"]);
        } else {
            //检查游戏ID的玩家是否存在 
            $post_data = array ("uno" =>$param["UNO"]);
            $gameData = parent::HttpPost("User",$post_data); 
            $data = json_decode($gameData,true);
            if(count($data["result"])!=1){
                parent::echoFail("关联的游戏ID异常");
            } 
            $param["GAMEID"]=$data["result"][0]["ID"];
            $param["LEVEL"] = $this->agent["LEVEL"]+1;
            if($this->agent["LEVEL"]==SysDict::$AGENTSTATE)
                $param["LEVEL"] = 2;
            $param["PAID"] = $this->agent["AID"]; 
            $param["ROOMCARD"] = 0;
            $param["GOLD"] = 0; 
            $param["SOURCE"] = 1; 
            $result = $this->AgentModel->saveAgentInfo($param,true);
        }
        if ($result) {
            parent::echoSuccess("保存成功");
        } else {
            parent::echoFail("保存数据失败");
        }
    }

    public function delAgent() {
        $aid = parent::getParam("aid");
        $paid = $this->agent["AID"];
        $result = $this->AgentModel->delAgent($aid, $paid);
        if ($result) {
            parent::echoSuccess("删除成功");
        } else {
            parent::echoFail("删除数据失败");
        }
    }
    
    public function transferRCard(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "下级管理";
        $data["menuSub"]      = "转账给代理商";
        $data["menuDetail"]   = "";  
        $data["lowerAgent"] = "";
        $data["AID"] = parent::getParam("aid");
        if(!empty($data["AID"])){
            $paid="";
            if($this->agent["LEVEL"] != SysDict::$ANGENTLEVEL["admin"] && $this->agent["LEVEL"] != SysDict::$ANGENTLEVEL["head"])
                $paid = $this->agent["AID"];  
            $lowerAgent = $this->AgentModel->getAgentById($data["AID"],  $paid);
            if($lowerAgent!=""){
                $data["lowerAgent"] = $lowerAgent;
            }
        }  
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('agentransferRC');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function getAgentById(){
        $aid = parent::getParam("aid");
        $paid="";
        if($this->agent["LEVEL"] != SysDict::$ANGENTLEVEL["admin"] && $this->agent["LEVEL"] != SysDict::$ANGENTLEVEL["head"])
            $paid = $this->agent["AID"];  
        $lowerAgent = $this->AgentModel->getAgentById($aid,  $paid); 
        parent::echoSuccess($lowerAgent); 
    }
    //转账
    public function doTransferRCard(){
        $aid = parent::getParam("AID");//对方账户
        $num = (int)parent::getParam("cardnum"); 
        $paid = $this->agent["AID"]; 
        $level = $this->agent["LEVEL"]; 
        $type = SysDict::$LOGTYPE["rctransfer"];
        $type2 = SysDict::$LOGTYPE["get_transfer_card"];  
        $id = StringUtil::orderid(23, SysDict::$EXUID["transfer_card"]);
        $id2 = StringUtil::orderid(23, SysDict::$EXUID["get_transfer_card"]);
        if(parent::isAdmin($level) || parent::isHead($level)){//这属于平台赠送
            $paid = SysDict::$SYSTEM_AGENT;
            $type = SysDict::$LOGTYPE["sys_give_card"];
            $type2= SysDict::$LOGTYPE["get_sys_give_card"];
            $id = StringUtil::orderid(23, SysDict::$EXUID["sys_give_card"]);
            $id2 = StringUtil::orderid(23, SysDict::$EXUID["get_sys_give_card"]); 
        }
        //检查我的房卡够不够转账的
        if( $this->agent["ROOMCARD"]<$num){
            parent::echoFail("抱歉，您的房卡余额不足");
            return;
        } 
        $result = $this->AgentModel->transferRCard($num,$aid, $paid,$level+1);
        if ($result) {
            //写入日志
            //转账
            $logParam["ID"] = $id; 
            $logParam["OPERATOR"]=$this->agent["AID"]; 
            $logParam["TYPE"] = $type; 
            $logParam["NUM"] = 0-$num; 
            $logParam["AID"]=$paid;
            $logParam["TOAID"] = $aid;
            $logParam["MONEY"] = 0; 
            $logParam["ACTUALPAY"] = 0;
            $logParam["RCID"] = $result; 
            //收到转账
            $logParam2["ID"] = $id2; 
            $logParam2["OPERATOR"]=$this->agent["AID"]; 
            $logParam2["TYPE"] = $type2; 
            $logParam2["NUM"] = $num; 
            $logParam2["AID"]=$aid;
            $logParam2["TOAID"] = $paid;
            $logParam2["MONEY"] = 0; 
            $logParam2["ACTUALPAY"] = 0;
            $logParam2["RCID"] = $result; 
            
            $this->LogModel->saveRcLog($logParam,$logParam2);
            parent::echoSuccess("房卡转账成功");
        } else {
            parent::echoFail("房卡转账失败");
        }
    }
    
    //分享推广
    public function share(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "下级管理";
        $data["menuSub"]      = "分享推广";
        $data["menuDetail"]   = "";  
        $data["scontent"] = "";
        $data["url"] = StringUtil::getHttpHost()."/Agent/regAgent?aid=".$this->agent["AID"];;
        //生成二维码
        $filename = "static/qr/".$this->agent["AID"].".png";
        if(!is_file($filename)){  
//            $value = StringUtil::getHttpHost()."/Agent/regAgent?aid=".$this->agent["AID"];
            $value = $data["url"];
            QRcode::png($value, $filename, "L", 8, 2);
        }
        $data["qr"] = $filename;
        
        $share = $this->AgentModel->getShareContent($this->agent["AID"]);
        if($share!="")
             $data["scontent"] = $share[0]["CONTENT"];
        
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('agentShare');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function saveContent(){
        $param        = $_REQUEST;
        $aid = $this->agent["AID"];
        $param["AID"] = $aid;
        $data = $this->AgentModel->getShareContent($aid);
        $res = 0;
        if($data!=""){
            //修改
            $res = $this->AgentModel->updateShareContent($param);
        }else{
            //新增
            $res = $this->AgentModel->saveShareContent($param);
        }
        if($res){
            parent::echoSuccess("保存成功");
        }
         parent::echoFail("保存失败");
    }

    //更多模板
    public function more(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "下级管理";
        $data["menuSub"]      = "二维码模板";
        $data["menuDetail"]   = ""; 
        $data["qrfiles"] = [];
        //生成二维码 
        $qrFile = "static/qr/".$this->agent["AID"].".png"; 
        if(!is_file($qrFile)){
            $value = StringUtil::getHttpHost()."/Agent/regAgent?aid=".$this->agent["AID"]; 
            QRcode::png($value, $qrFile, "L", 8, 2);
        }
        if(is_file($qrFile)){
            $tCount = 4;
            for($i=1;$i<=$tCount;$i++){
                $filename = "static/qr/".$this->agent["AID"]."_".$i.".png";
                if(!is_file($filename)){
                    $bgFile = "static/qr/photo".$i.".png";
                    $this->imgSplice($qrFile, $bgFile,$filename);
                }
                $data["qrfiles"][$i] = $filename;
            }
        }
        
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('agentShareQrMore');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function imgSplice($QR,$bg,$filename){
        $QR = imagecreatefromstring(file_get_contents($QR)); 
        $bg = imagecreatefromstring(file_get_contents($bg));  
        $QR_width = imagesx($QR);//二维码图片宽度 
        $QR_height = imagesy($QR);//二维码图片高度 
        $bg_width = imagesx($bg);//背景图片宽度 
        $bg_height = imagesy($bg);//背景图片高度
        $x = ($bg_width-$QR_width)/2;
        $y = ($bg_height-$QR_height)/2;
        imagecopyresampled($bg,$QR, $x, $y, 0, 0, 
                $QR_width, $QR_height, $QR_width, $QR_height);
        imagepng($bg, $filename);
        
    }
    
    //注册成为下线代理商
    public function regAgent() {
//        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "代理商";
        $data["menuSub"]      = "新增代理商";
        $data["menuDetail"]   = "";
        $data["isreg"] = "";
        $data["agent"] = "";
        if(isset($_REQUEST["aid"]) && !empty($_REQUEST["aid"])) { 
            $isreg = get_cookie("isreg");
            
            $data["paid"] = $_REQUEST["aid"];
            $data["isreg"] = $isreg;  
            if($isreg!=""){
                //查询代理商信息
                $agent = $this->AgentModel->getAgentById($isreg);
                $data["isreg"] = $isreg;
                $data["agent"] = json_encode($agent); 
            }
            $this->load->view('agentreg',$data); 
        }else{
            echo "参数错误";
        }
    }  
    
    //发送手机验证码
    public function sendVCode(){
        $cellphone = parent::getParam("phone"); 
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
    
    public function doRegAgent() {
        $param = $_REQUEST; 
        if(empty($param)){
            parent::echoFail("保存数据失败");
        }
        $aid = $param["PAID"];
        // 验证微信号是否已经存在
        if ($this->AgentModel->isWXIDExist($param["WXID"])) {
            parent::echoFail("微信号已经存在");
        }
        // 验证手机号是否已经存在
        if ($this->AgentModel->isMobileExist($param["CELLPHONE"])) {
            parent::echoFail("手机号已经存在");
        }
        //检查游戏ID是否已经存在
        if ($this->AgentModel->isGIDExist($param["UNO"])) {
            parent::echoFail("游戏ID已经存在");
        }
        //检查游戏ID的玩家是否存在 
        $post_data = array("uno" =>$param["UNO"]);
        $gamerData = parent::HttpPost("User",$post_data); 
        $gamer = json_decode($gamerData,true);
        if(count($gamer["result"])!=1){
            parent::echoFail("游戏ID填写错误");
        }
        
        // 保存数据
        $param["GAMEID"]=$gamer["result"][0]["ID"];
        $param["LEVEL"] = 3; 
        $param["ROOMCARD"] = 0;
        $param["GOLD"] = 0; 
        $param["WXNICKNAME"] = $param["WXID"];
        $param["SOURCE"] = 2; 
        $result = $this->AgentModel->saveAgentInfo($param,true);  
        if($result!=""){ 
            set_cookie("isreg", $result, 30 * 24 * 60 * 60);
            $data = $this->AgentModel->getAgentInfo($result); 
            parent::echoSuccess($data);
        } else {
            parent::echoFail("保存数据失败");
        }
    }
}
