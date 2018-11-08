<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php"); 

class Lotto extends BaseC {

    public function __construct() {
        parent::__construct(); 
    }
    public function test(){
        echo "hello";
    }
    //兑奖记录
    public function redeemRc() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "代理商";
        $data["menuSub"]      = "兑奖记录";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('lotto/redeemrc');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function redeemList(){
        $post = array ();
        $result = parent::HttpPost("redeemRc",$post);
        $data = json_decode($result,true); 
        parent::echoLocalBootstrapTableData($data["result"],count($data["result"]));
    }
    public function redeem(){
        $post = array ("id"=>$_REQUEST["id"]);
        $result = parent::HttpPost("redeem",$post);
        $data = json_decode($result,true);
        if($data["code"]=="1")
            parent::echoSuccess ("");
        parent::echoFail($data["message"]);
    }

        //7c2abbe0524611e7a41a5b3aeab91d00 
    public function index() {
        if(empty($_REQUEST["uid"]))
            parent::echoFail ("参数uid缺失"); 
        
        $uid = $_REQUEST["uid"]; 
        $data["uid"] = $uid;
        $this->load->view('lotto/frame',$data); 
    } 
    
    public function show($uid) {
        //获取玩家积分
        $post = array ("uid" =>$uid);
        $userData = parent::HttpPost("UserScore",$post);
        $userrows = json_decode($userData,true);
        $log = $this->getlog();
        $score = 0;
        $name = "";
        $total = 10;
        if($userrows["code"]==1){
            $score = $userrows["result"]["score"];
            $name = $userrows["result"]["name"];
            $total = $userrows["result"]["total"];
        }
        $data["uid"] = $uid;
        $data["score"] = $score;
        $data["name"] = $name;
        $data["total"] = $total; 
        $data["log"] = $log;  
        $this->load->view('lotto/index',$data); 
    }
    public function share() {
        //获取玩家积分
        if(empty($_REQUEST["name"]))
            parent::echoFail ("参数name缺失");
        if(empty($_REQUEST["award"]))
            parent::echoFail ("参数award缺失");
        
        $data["name"]= $_REQUEST["name"];
        $data["award"] = $_REQUEST["award"];
        $this->load->view('lotto/share',$data); 
    }
    public function dolog(){ 
        $uid = $_REQUEST["uid"];
        $award = $_REQUEST["award"];
        $type = 1;
        $score = -10;
        $code = StringUtil::randStr(10);//兑换码
        $content["code"] = $code;
        $content["award"] = $award;//奖项
        $content["award2"] = "水果";//奖项
        $c = json_encode($content);
//        $content = "抽中".$award;//"抽中一等奖"
        $post = array ("uid" =>$uid,"t"=>$type,"c"=>$c,"s"=>$score);
        $data = parent::HttpPost("dolog",$post);
        $res = json_decode($data,true); 
        if($res["code"]=="1"){
            $mail = $this->sendmail($uid, $award,$code); 
            parent::echoSuccess($mail);
        }
        parent::echoFail("日志记录错误");
    }
    public function sendmail($uid,$award,$code){ 
//        $award = "一等奖";
        $desc = "亲爱的玩家，恭喜您在积分抽奖活动中获得".$award."，获得***水果一斤。以下是兑换码（需要你将信息分享到朋友圈才可以查看），请你尽快与客户lzjgqp004联系，完成兑换。";
        $title = "您中奖了"; 
        $ass = "cj,".$code.",".$award;
        $post = array ("uid" =>$uid,"title"=>$title,"desc"=>$desc,"ass"=>$ass);
        $data = parent::HttpPost("sendmail",$post);
//        print_r($data);
        return json_decode($data,true); 
    }
    //获取记录
    public function getlog(){
        $post = array ();
        $data = parent::HttpPost("getlog",$post);
        $res = json_decode($data,true); 
        return $res["result"]; 
    }
    public function logrefresh(){
        parent::echoSuccess($this->getlog());
    }
    
}