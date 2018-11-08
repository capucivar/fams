<?php
/* 
 * 游戏记录管理
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
class GameLogC extends BaseC {
    public function __construct() {
        parent::__construct();
        $this->load->model("GameLogModel"); 
        $this->load->model("UserModel");
    }
    public function index(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "游戏记录管理";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gamelog/gamelog');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function getLog(){
        //默认查询当天往前7天
        $edate = empty($_REQUEST["e"])?date("Y-m-d",  time()):$_REQUEST["e"];
        $sdate = empty($_REQUEST["s"])?date("Y-m-d",strtotime("-7 day")):$_REQUEST["s"];  
        $data = $this->GameLogModel->getGameLog($sdate,$edate);
        parent::echoLocalBootstrapTableData($data, count($data));
    }
    
    /**
     * 直接调用接口获取数据
     */
    public function getLog_20171228(){
        $limit = parent::getParam("limit");
        $offset = parent::getParam("offset"); 
        //获取玩家上级ID
        $gamers = $this->UserModel->getUpperId();
        //游戏记录数据，默认当天
        $etime = date("Y-m-d", time())." 23:59"; 
        $stime = date("Y-m-d", time())." 00:00"; 
        $postData = array ("uno" =>"","s"=>$stime,"e"=>$etime,"offset"=>$offset,"limit"=>$limit); 
        $gameData = parent::HttpPost("GameLog",$postData); 
        $data = json_decode($gameData,true); 
        $count = count($data["result"]);
        $result = []; 
        foreach ($data["result"] as $val) {
            $uid = $val["uid"]; 
            $paid="";
            foreach($gamers as $gamer){
                if($gamer["GID"]==$uid){
                    $paid = $gamer["PAID"]; 
                    break;
                }
            }
            $arr["rno"] = $val["rno"];
            $arr["upperid"] = $paid;
            $arr["uno"] = $val["uno"];
            $arr["name"] = $val["name"];
            $arr["score"] = $val["score"];
            $arr["rcount"] = $val["rcount"];
            $arr["payType"] = $val["payType"];
            $arr["stime"] = $val["stime"];
            $arr["minute"] = $val["minute"];
            $arr["roundCount"] = $val["roundCount"];
            $arr["juCount"] = $val["juCount"];
            $game_users = "";
            $others = $val["others"];
            foreach ($others as $user) { 
                if(empty($user))
                    continue; 
                if(!isset($user["name"]))
                    $user["name"]="无名";
                if(!isset($user["allScore"]))
                    $user["allScore"]="0";
                $game_users.=$user["name"].":".$user["allScore"]."；";
            }
            $arr["others"] = $game_users;
            array_push($result,$arr); 
        }
        parent::echoLocalBootstrapTableData($result, $count);
    }
}