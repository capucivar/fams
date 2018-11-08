<?php
/**
 * 系统管理员代理商管理类
 * **/
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php");
include_once(APP_PATH_L . "phpqrcode.php");

class AgentAdmin extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("AgentModel");
        $this->load->model("UserModel");
        $this->load->model("LogModel");
        $this->load->model("ChangePwdModel");
    }
   
    //渠道代理商
    public function fagentList() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "代理商";
        $data["menuSub"]      = "渠道代理商管理";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('agentlistf');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //玩家代理商
    public function sagentList($id=""){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "代理商";
        $data["menuSub"]      = "玩家代理商管理";
        $data["menuDetail"]   = "";
        $data["paid"] = $id;
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('agentlists');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    //玩家管理
    public function gamerList($id=""){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "玩家";
        $data["menuSub"]      = "玩家管理";
        $data["menuDetail"]   = "";
        $data["paid"] = $id;
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('agentlistg');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //查询渠道代理商数据
    public function getfAgentList() { 
        $search = parent::getParam("search");
        //渠道代理商数据
        $rows   = $this->AgentModel->getLowerAgentListData("", $search,  SysDict::$ANGENTLEVEL["one"]);
        $count  = $this->AgentModel->getLowerAgentListDataCount("", $search,SysDict::$ANGENTLEVEL["one"]);
        //玩家代理商数据
        $lrows = $this->AgentModel->getLowerAgentListData("", "",SysDict::$ANGENTLEVEL["two"]); 
        //玩家数据
        $grows = $this->UserModel->getUserListById(); 
        //玩家组局数据
        $postData = array ("uid" =>"","bt"=>"1","et"=>"1");
        $gamerData = parent::HttpPost("UsersGameCount",$postData);
        $gamerows = json_decode($gamerData,true); 
        $result = [];
        
        foreach($rows as $row){
            $aid = $row["AID"];
            $lowerc = 0;//玩家代理商的数量
            $gamerc = 0;//玩家的数量
            $gameNum = 0;//玩家组局数量
            $lids = [];//玩家代理商的ID
            $gids = [];//玩家的ID
            //封号时长
            $validate = $row["INVALIDATE"];
            $curdate = date("y-m-d h:i:s",  time());
            $state = "正常"; 
            if(strtotime($validate)>strtotime($curdate)){
                $state = "被封号至：".$validate;
            }
            foreach ($lrows as $lrow) {
                if($lrow["PAID"] == $aid){
                    $lowerc++; 
                    array_push($lids, $lrow["AID"]);
                }
            }
            foreach($grows as $grow){
                if(in_array($grow["PAID"], $lids)){
                    $gamerc++;
                    array_push($gids, $grow["GID"]);
                } 
            }
            foreach ($gamerows["result"] as $gamerow) {
                if(in_array($gamerow["id"], $gids)){
                    $gameNum+=(int)$gamerow["NUM"];
                }
            }
            array_push($result,array_merge($row, array('LOWERNUM'=>$lowerc,'GAMERNUM'=>$gamerc,'GAMENUM'=>$gameNum,'IDSTATE'=>$state)));
        }
        parent::echoLocalBootstrapTableData($result, $count);
        
    }
    //查询玩家代理商数据
    public function getsAgentList($id="") {
        $search = parent::getParam("search");  
        $rows   = $this->AgentModel->getLowerAgentListData($id, $search,  SysDict::$ANGENTLEVEL["two"]);
        $count  = $this->AgentModel->getLowerAgentListDataCount($id, $search,SysDict::$ANGENTLEVEL["two"]);
        //绑定的玩家数量
        $grows = $this->UserModel->getUserListById(); 
        //绑定玩家的组局数
        $postData = array ("uid" =>"","bt"=>"1","et"=>"1");
        $gamerData = parent::HttpPost("UsersGameCount",$postData);
        $gamerows = json_decode($gamerData,true); 
        $result = [];
        foreach ($rows as $row) {
            $aid = $row["AID"];
            $gamerc = 0;//玩家的数量
            $gameNum = 0;//玩家组局数量
            $gids = [];//玩家的ID
            
            $validate = $row["INVALIDATE"];
            $curdate = date("y-m-d h:i:s",  time());
            $state = "正常"; 
            if(strtotime($validate)>strtotime($curdate)){
                $state = "被封号至：".$validate;
            }
            foreach($grows as $grow){
                if($grow["PAID"]==$aid){
                    $gamerc++;
                    array_push($gids, $grow["GID"]);
                } 
            } 
            foreach ($gamerows["result"] as $gamerow) {
                if(in_array($gamerow["id"], $gids)){
                    $gameNum+=(int)$gamerow["NUM"];
                }
            }
            array_push($result,array_merge($row, array('GAMERNUM'=>$gamerc,'GAMENUM'=>$gameNum,'IDSTATE'=>$state)));
        }
        parent::echoLocalBootstrapTableData($result, $count);
    }
    //查询绑定玩家数据
    public function getGamerList($id="") { 
        $search = parent::getParam("search");  
        $rows   = $this->UserModel->getUserListData($id, $search);
        $count = count($rows); 
//        $count  = $this->UserModel->getUserListDataCount($id, $search);

        //玩家今日玩局数
        $bt = date("Y-m-d", time())." 00:00";
        $et = date("Y-m-d", time())." 23:59";
        $postData = array ("uid" =>"","bt"=>$bt,"et"=>$et);
        $gamerData = parent::HttpPost("UsersGameCount",$postData);
        $gamerows = json_decode($gamerData,true); 

        //玩家累计玩局数
        $postData2 = array ("uid" =>"","bt"=>"1","et"=>"1");
        $gamerData2 = parent::HttpPost("UsersGameCount",$postData2);
        $gamerows2 = json_decode($gamerData2,true); 
        $result = [];
        foreach ($rows as $row) {
            $gid = $row["GID"];
            $dayNum = 0;
            $num = 0;
            $uno = "";
            
            //封号时长
            $validate = $row["INVALIDATE"];
            $curdate = date("y-m-d h:i:s",  time());
            $state = "正常"; 
            if(strtotime($validate)>strtotime($curdate)){
                $state = "被封号至：".$validate;
            }
            
            foreach ($gamerows["result"] as $grow) { 
                if($grow["id"]==$gid){
                    $dayNum = $grow["NUM"];
//                    $uno = $grow["uno"];
                    break;
                }
            }
            foreach ($gamerows2["result"] as $grow) {
                if($grow["id"]==$gid){
                    $num = $grow["NUM"];
                    break;
                }
            }
            array_push($result,array_merge($row, array('DAYNUM'=>$dayNum,'NUM'=>$num,'IDSTATE'=>$state)));
        }
        parent::echoLocalBootstrapTableData($result, $count);
    } 
    public function updateValidate(){
        $aid = parent::getParam("aid");
        $val = parent::getParam("val");
        $validate = "";
        $time = date("H:i:s", time());
        switch ($val){
            case "1"://+7天
                $validate = date("Y-m-d",strtotime("+1 week"))." ".$time;
                break;
            case "2"://+1月
                $validate = date("Y-m-d",strtotime("+1 month"))." ".$time;
                break; 
            case "3"://永久
                $validate = "9999-12-31";
                break;
        } 
        $state = $val=="3"?2:1; 
        $result = $this->AgentModel->updateValidate($validate,$aid,$state);
        if ($result) {
            parent::echoSuccess("已封号");
        } else {
            parent::echoFail("封号失败");
        }
    }
    //解封账号
    public function unban(){
        $aid = parent::getParam("aid");
        $result = $this->AgentModel->updateValidate("",$aid);
        if ($result) {
            parent::echoSuccess("账号已经成功解封");
        } else {
            parent::echoFail("解封失败，请联系系统管理员");
        }
    }
    //玩家账号解封
    public function unbanGamer(){
        $pid = parent::getParam("pid"); 
        $result = $this->UserModel->updateValidate("",$pid);
        if ($result) {
            parent::echoSuccess("账号已经成功解封");
        } else {
            parent::echoFail("解封失败，请联系系统管理员");
        }
    }
    public function updateGamerValidate(){
        $pid = parent::getParam("pid");
        $val = parent::getParam("val");
        $validate = "";
        $time = date("H:i:s", time());
        switch ($val){
            case "1"://+7天
                $validate = date("Y-m-d",strtotime("+1 week"))." ".$time;
                break;
            case "2"://+1月
                $validate = date("Y-m-d",strtotime("+1 month"))." ".$time;
                break; 
            case "3"://永久
                $validate = "9999-12-31";
                break;
        }
        $state = $val=="3"?2:1; 
        $result = $this->UserModel->updateValidate($validate,$pid,$state);
        if ($result) {
            parent::echoSuccess("已封号");
        } else {
            parent::echoFail("封号失败");
        }
    } 
    public function giveRCard(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "渠道代理商管理";
        $data["menuSub"]      = "赠送房卡";
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
        $lowerAgent = $this->AgentModel->getAgentById($aid,  $this->agent["AID"]);
        echo parent::echoSuccess($lowerAgent); 
    }
//    
//    //平台赠送房卡
//    public function doTransferRCard(){
//        $aid = parent::getParam("AID");//对方账户
//        $num = (int)parent::getParam("cardnum"); 
//        $paid = $this->agent["AID"]; 
//        $level = $this->agent["LEVEL"]; 
//        //检查我的房卡够不够转账的
//        if($this->agent["ROOMCARD"]<$num){
//            parent::echoFail("抱歉，您的房卡余额不足");
//            return;
//        }
//        $result = $this->AgentModel->transferRCard($num,$aid, $paid,$level+1);
//        if ($result) {
//            //写入日志
//            //转账
//            $logParam["ID"]=StringUtil::orderid(23, SysDict::$EXUID["transfer_card"]); 
//            $logParam["OPERATOR"]=$paid; 
//            $logParam["TYPE"] = SysDict::$LOGTYPE["rctransfer"]; 
//            $logParam["NUM"] = $num; 
//            $logParam["AID"]=$paid;
//            $logParam["TOAID"] = $aid;
//            $logParam["MONEY"] = 0; 
//            $logParam["ACTUALPAY"] = 0;
//            $logParam["RCID"] = $result; 
//            //收到转账
//            $logParam2["ID"]=StringUtil::orderid(23, SysDict::$EXUID["get_transfer_card"]); 
//            $logParam2["OPERATOR"]=$paid; 
//            $logParam2["TYPE"] = SysDict::$LOGTYPE["get_transfer_card"]; 
//            $logParam2["NUM"] = $num; 
//            $logParam2["AID"]=$aid;
//            $logParam2["TOAID"] = $paid;
//            $logParam2["MONEY"] = 0; 
//            $logParam2["ACTUALPAY"] = 0;
//            $logParam2["RCID"] = $result; 
//            
//            $this->LogModel->saveRcLog($logParam,$logParam2);
//            parent::echoSuccess("房卡转账成功");
//        } else {
//            parent::echoFail("房卡转账失败");
//        }
//    }
    //解绑玩家
    public function unbind(){
        $pid = parent::getParam("pid");
        $result = $this->UserModel->unbind($pid);
        if ($result) {
            parent::echoSuccess("已解绑");
        } else {
            parent::echoFail("解绑失败");
        }
    }
    
}
