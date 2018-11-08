<?php
/* 
 * 游戏记录管理
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
class GhostManC extends BaseC {
    private $step = 0;
    private $content = "";
    private $users = array();
    public function __construct() {
        parent::__construct();
        $this->load->model("GhostManModel");  
        $this->users = $this->GhostManModel->getUserInfo(); 
    }
    public function index(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "游戏记录管理";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 
        $this->getData();
        
        $data["content"] = $this->content;
        $this->load->view('common/header', $data); 
//        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('ghostMan');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function getData(){ 
        $postData = array ("date"=>"2018-01-04","rid"=>""); 
        $resData = parent::HttpPost("getData",$postData, $this->GHOSTMAN_LOCAL);  
        $data = json_decode($resData,true);
        $rows = $data["result"]; 
        foreach ($rows as $row) {
            $this->step++;
            //截取时间
            $time = substr($row, 1, 19);
            $content_end_index = strrchr("[] []", $row);
            $str_content = substr($row, 36,strlen($row)-43);
            $json_content = json_decode($str_content,  true);
            $type="";
            if(isset($json_content["message_date"])){
                $type = $json_content["message_date"]["type"];
            }else{
                $type = $json_content["type"];
            }
            $line = $this->getLogType($type, $json_content, $time);
            $this->echoLine($line); 
        }
    }
    
    private function getLogType($type,$content,$time){
        switch ($type) {
            case "enter":
                return $this->EnterMsg($content, $time); 
            case "room":
                return $this->ParseRoomData($content, $time);
            case "step":
                return $this->StepMsg($content, $time);
            case "stepAry":
                return $this->stepAryMsg($content, $time);
            case "uid"://发放轮次数据
                return $this->uidMsg($content, $time);
            default:
                break;
        }
    }
    
    private function EnterMsg($content,$time){ 
        $msg=" 【".$time."】【enter】";
        $uid = $content["message_date"]["uid"];
        //根据uid 获取用户信息 
        $user = $this->getUser($uid);
        $username = "无名氏";
        $uno="000000";
        if(count($user)>0){
            $username = $user["NICKNAME"];
            $uno = $user["UNO"];
        }
        $msg.="用户 ".$username."（".$uno."）进入房间";
        return $msg;
    }
    //请求轮次数据
    private function StepMsg($content,$time){
        $step = $content["message_date"]["step"];
        $msg=" 【".$time."】【请求第".$step."轮次数据】"; 
        $msg.=json_encode($content);
        return $msg;
    }
    private function stepAryMsg($content,$time){
        $msg=" 【".$time."】【stepAry】".  json_encode($content);
        return $msg;
    }
    private function uidMsg($content,$time){
        $step = $content["content"]["step"]["step"];
        $msg=" 【".$time."】【发放第".$step."轮次数据】".  json_encode($content);
        return $msg;
    }

    private function ParseRoomData($content,$time){
        $ctype = "room";
        if(isset($content["content"]["type"]))
            $ctype = $content["content"]["type"]; 
        switch ($ctype) {
            case "logout":
                return $this->LogOutMsg($content, $time);   
            case "rd":
                return $this->RoomRdMsg($content, $time); 
            case "enter"://同步房间数据
                return $this->RoomRoomMsg($content, $time); 
            case "users"://同步用户数据
                return $this->RoomUsersMsg($content, $time); 
            case "step"://获取轮次数据
                return $this->RoomStepMsg($content, $time); 
            case "chu"://出牌
                return $this->RoomChuMsg($content, $time); 
            case "rdoverride"://断杠
                return $this->RoomRdoverrideMsg($content, $time);
        }
    }
    private function LogOutMsg($content,$time){
        $msg="【".$time."】【logout】";
        $uids = $content["content"]["uids"];
        //根据uid 获取用户信息 
        $users = $this->getUsers($uids);
        $msg.="用户 【".$users."】离线";
        return $msg;
    }
    
    private function RoomRdMsg($content,$time){
        $msg = "【".$time."】【操作】";
        $action = $content["content"]["rd"]["action"];  
        $action_content = implode(",", $content["content"]["rd"]["content"]);  
        $level = implode(",", $content["content"]["rd"]["level"]); 
        $pos = $content["content"]["rd"]["pos"];
        $step = $content["content"]["rd"]["step"]; 
        $msg .= "step：".$step." pos：".$pos." action：".$action."（".$action_content."） level：".$level;
        return $msg; 
    }
    private function RoomRoomMsg($content,$time){ 
        $msg = "【".$time."】【同步房间数据】 ";  
        return $msg;
    }
    private function RoomUsersMsg($content,$time){  
        $msg = "【".$time."】【同步用户数据】";  
        return $msg;
    }
    
    private function RoomStepMsg($content,$time){ 
        $step = $content["content"]["step"]["step"];
        $msg = "【".$time."】【获取第".$step."轮次数据】".  json_encode($content);  
        return $msg;
    }
    private function RoomChuMsg($content,$time){  
        $msg = "【".$time."】【出牌】".  json_encode($content["content"]["other"]);  
        return $msg;
    }
    private function RoomRdoverrideMsg($content,$time){
        $msg = "【".$time."】【断杠】".  json_encode($content["content"]["rd"]);  
        return $msg;
    }
    public function getUser($id){
        $rows = $this->users; 
        $res = array();
        foreach ($rows as $row) {
            if($id==$row["GID"]){
                $res = $row;
                break;
            }
        }
        return $res;
    }
    private function getUsers($ids){
        $rows = $this->users;
        $unames = ""; 
        foreach ($rows as $row) { 
            if(in_array($row["GID"], $ids))
                $unames.=$row["NICKNAME"].",";
        }
        return $unames;
    }
    private function echoLine($content){
        $msg = "<b>".$this->step."：</b>".$content."<br/>";
        $this->content.=$msg; 
//        return 
    }
}