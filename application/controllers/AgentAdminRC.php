<?php
/**
 * 系统管理员房卡管理
 * **/
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php");
include_once(APP_PATH_L . "phpqrcode.php");

class AgentAdminRC extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("RoomcardModel"); 
        $this->load->model("LogModel");
    } 
    public function test(){
        echo StringUtil::uuid(date("ymd"));
    }


    //生成赠送房卡
    public function rcnnew() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "房卡管理";
        $data["menuSub"]      = "生成赠送房卡";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('rcnew');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //房卡记录
    public function rclog(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "房卡管理";
        $data["menuSub"]      = "房卡记录";
        $data["menuDetail"]   = ""; 
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('rclog');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //平台生成赠送房卡
    public function save(){
        $aid = SysDict::$SYSTEM_AGENT; 
        $num = parent::getParam("NUM");
        $mark = parent::getParam("MARK");
        $area = parent::getParam("areaProvince").",".parent::getParam("areaCity").",".parent::getParam("areaTown");
        $type = SysDict::$LOGTYPE["sys_new_card"];  
        $res = $this->RoomcardModel->saveRoomCard($aid,$num,$mark,$area,$type);
        if($res){
             //写入日志
            $rcid = StringUtil::orderid(23, SysDict::$EXUID["sys_new_card"]);
            $logParam["ID"] = $rcid;
            $logParam["OPERATOR"]=$this->agent["AID"];
            $logParam["AID"]=$aid;
            $logParam["TOAID"]=$aid;
            $logParam["TYPE"] = SysDict::$LOGTYPE["sys_new_card"];
            $logParam["NUM"] = $num; 
            $logParam["MONEY"] = 0; 
            $logParam["ACTUALPAY"] = 0;
            $logParam["AREA"] = $area;
            $logParam["MARK"] = $mark;
            $res2 = $this->LogModel->saveRcLog($logParam);
            if($res2){
                //记录日志   
                $content["msg"]="平台生成赠送房卡"; 
                $content["num"]=$num;
                $content["operator"]=$this->agent["AID"];
                $content["aid"]=$aid;
                $content["level"]= SysDict::$SYSTEM_AGENT_LEVEL;
                $content["rcid"] = $rcid; 
                
                $data["LTYPE"]= SysDict::$LOGTYPE["sys_new_card"];
                $data["AID"]= $aid;
                $data["CONTENT"] = json_encode($content);
                $this->LogModel->saveLog($data);
            }
            parent::echoSuccess("赠送房卡生成成功");
        }else{
            parent::echoSuccess("赠送房卡生成失败");
        }
    }
    public function save_bak(){
        $aid = $this->agent["AID"]; 
        $num = parent::getParam("NUM");
        $mark = parent::getParam("MARK");
        $area = parent::getParam("areaProvince").",".parent::getParam("areaCity").",".parent::getParam("areaTown");
        $type = SysDict::$LOGTYPE["sys_new_card"];  
        $res = $this->RoomcardModel->saveRoomCard($aid,$num,$mark,$area,$type);
        if($res){
             //写入日志
            $rcid = StringUtil::uuid();
            $logParam["RCID"] = $rcid;
            $logParam["AID"]=$aid;
            $logParam["LTYPE"] = SysDict::$LOGTYPE["sys_new_card"];
            $logParam["RCOUNT"] = $num;
            $logParam["RCTO"] = "";
            $logParam["MONEY"] = 0; 
            $logParam["ACTUALPAY"] = 0;
            $logParam["AREA"] = $area;
            $logParam["RCLEVEL"] = "";
            $res2 = $this->LogModel->saveRcLog($logParam);
            if($res2){
                //记录日志   
                $content["msg"]="平台生成赠送房卡"; 
                $content["num"]=$num;
                $content["aid"]=$aid;
                $content["level"]=  $this->agent["LEVEL"];
                $content["rcid"] = $rcid; 
                
                $data["LTYPE"]= SysDict::$LOGTYPE["sys_new_card"];
                $data["AID"]= $aid;
                $data["CONTENT"] = json_encode($content);
                $this->LogModel->saveLog($data);
            }
            parent::echoSuccess("赠送房卡生成成功");
        }else{
            parent::echoSuccess("赠送房卡生成失败");
        }
    }
    public function rcloglist(){
        //默认查询最近七天数据
        $edate = empty($_REQUEST["e"])?date("Y-m-d",  time()):$_REQUEST["e"];
        $sdate = empty($_REQUEST["s"])?date("Y-m-d",strtotime("-7 day")):$_REQUEST["s"];
        $edate .= " 23:59";
        $sdate .= " 00:00"; 
        $level = empty($_REQUEST["l"])?"":$_REQUEST["l"];
        $logs = $this->RoomcardModel->getRclog($sdate,$edate,$level);
        $count=  count($logs); 
        parent::echoLocalBootstrapTableData($logs, $count);
    }
    public function rcloglist_bak(){
        //默认查询最近七天数据
        $edate = empty($_REQUEST["e"])?date("Y-m-d",  time()):$_REQUEST["e"];
        $sdate = empty($_REQUEST["s"])?date("Y-m-d",strtotime("-7 day")):$_REQUEST["s"];
        $edate .= " 23:59";
        $sdate .= " 00:00"; 
        $logs = $this->RoomcardModel->getRclog($sdate,$edate);
        $count=  count($logs);
        //玩家数据
        $postData = array ();
        $gamerData = parent::HttpPost("User",$postData);
        $gamerows = json_decode($gamerData,true); 
        $gamers = $gamerows["result"]; 
        $result = [];  
        foreach ($logs as $log) {
            $data["ID"] = $log["LID"];//记录ID
            $data["UNO"] = "";//玩家ID
            $data["GNAME"] = "";//玩家昵称
            $data["AID"] = "";//代理商ID
            $data["LEVEL"] = "";//代理商等级
            $data["RNO"] = "";//房间号
            $data["PAID"] = "";//绑定代理商ID
            $data["NUM"] = "";//房卡数量
            $data["TYPE"] = ""; 
            $data["LTYPE"] = $log["LTYPE"]; 
            $data["CTIME"] = $log["CTIME"]; 
            $content = json_decode($log["CONTENT"],true); 
            switch ($log["LTYPE"]) {
                case SysDict::$LOGTYPE["giverc"]://平台赠送给玩家：完成任务赠送房卡
                    $user = $this->getUserById($log["AID"], $gamers);
                     if(!empty($user)){
                        $data["UNO"] = $user["UNO"];
                        $data["GNAME"] = $user["NAME"]; 
                     }
                     $data["PAID"] = empty($content["paid"])?"":$content["paid"]; 
                     $data["LEVEL"] = "玩家";
                     $data["NUM"] = $content["num"]; 
                     $data["TYPE"] = "任务完成领取房卡"; 
                    break;
                case SysDict::$LOGTYPE["gamerpay"]://玩家充值 
                    $user = $this->getUserById($log["AID"], $gamers);
                    if(!empty($user)){
                        $data["UNO"] = $user["UNO"];
                        $data["GNAME"] = $user["NAME"];  
                    } 
                    $data["PAID"] = empty($content["paid"])?"":$content["paid"]; 
                    $data["LEVEL"] = "玩家";
                    $data["NUM"] = $content["num"]; 
                    $data["TYPE"] = "玩家充值"; 
                    break;
                case SysDict::$LOGTYPE["sys_new_card"]://平台生成赠送房卡
                    $data["AID"] = $log["AID"];//代理商ID 
                    $data["LEVEL"] = SysDict::GET_AGENT_LEVEL($content["level"]);//代理商等级
                    $data["NUM"] = $content["num"];//房卡数量
                    $data["TYPE"] = "赠送生成"; 
                    break;
                default:
                    break;
            }
            array_push($result, $data);            
        }
        parent::echoLocalBootstrapTableData($result, $count);
    }
    private function getUserById($id,$arrs){
        foreach ($arrs as $arr) {
            if($id==$arr["ID"])
                return $arr;
        }
        return "";
    } 
}
