<?php
/* 
 * 接口
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");

class ServiceI extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("ServiceModel"); 
        $this->load->model("LogModel");
        $this->load->model("RoomcardModel");
        $this->load->model("UserModel");
    }
    public function index(){
//        echo "Hello,capucivar";
        $data["url"]="http://dlstest.dangmianyou.com/ServiceI/";
        $this->load->view('servicev',$data);
    }
    
    //预扣除房卡
    //param={"uid":"11","num":"3"}
    public function prederc(){  
        if(empty($_REQUEST["param"])) { 
            parent::echoFail ("必要参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE);
        if(empty($param_arr["uid"])){ 
            parent::echoFail ("参数uid缺失");
        }
        if(empty($param_arr["num"])){ 
            parent::echoFail ("参数num缺失");
        }
        $rno = empty($param_arr["rno"])?"9999":$param_arr["rno"];
        $rcid="";
        if(!empty($param_arr["uid"]) && !empty($param_arr["num"])){ 
            $uid = $param_arr["uid"];
            $num = $param_arr["num"];
            //查询房卡数量
            $count = $this->ServiceModel->getrc($uid);
            if((int)$count<$num){
                parent::echoFail ("房卡数量不足");
            }
            $rcid = $this->ServiceModel->prederc($uid,$num); 
        }
        if($rcid!=""){
            //记录日志 
            $content["msg"]="预扣房卡";
            $content["rcid"]=$rcid;
            $content["rno"] = $rno;
            
            $data["LTYPE"]= SysDict::$LOGTYPE["prederc"];
            $data["AID"]= $param_arr["uid"];
            $data["CONTENT"]= json_encode($content);
            $this->LogModel->saveLog($data);
            $output["rcid"] = $rcid;
            parent::echoSuccess($output); 
        }
        parent::echoFail ("数据有误");
    }
    //扣除房卡，扣除玩家的房卡，平台回收房卡
    //param={"uid":"11","rcid":"","rno":""}
    public function derc(){
        if(empty($_REQUEST["param"])) { 
            parent::echoFail ("必要参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE); 
        if(empty($param_arr["uid"])){ 
            parent::echoFail ("参数uid缺失");
        }
        if(empty($param_arr["rcid"])){ 
            parent::echoFail ("参数rcid缺失");
        } 
        $uid = $param_arr["uid"];
        $rcid = $param_arr["rcid"]; 
        $rno = empty($param_arr["rno"])?"----":$param_arr["rno"];
        $num = count(explode(',',$rcid)); 
        //查询玩家的pid
        $user = $this->ServiceModel->getPlayerById($uid);
        $pid = empty($user["PID"])?"":$user["PID"];
        $res = $this->ServiceModel->derc($uid,$rcid,$num,$rno,$pid);  
        if($res){
            //记录日志 
            //content内容
            $content["msg"]="扣除房卡";
            $content["rcid"]=$rcid;
            $content["rno"]=$rno;
            $content["num"]=$num;
            
            $data["LTYPE"]= SysDict::$LOGTYPE["derc"];
            $data["AID"]= $uid;
            $data["CONTENT"] = json_encode($content);
            $this->LogModel->saveLog($data);  
            parent::echoSuccess(""); 
        }
        parent::echoFail ("房卡扣除失败");
    }
    //重置房卡状态
    //param={"uid":"654321","rcid":"","rno":""}
    public function cancelderc(){
        if(empty($_REQUEST["param"])) { 
            parent::echoFail ("必要参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE); 
        if(empty($param_arr["uid"])){ 
            parent::echoFail ("参数uid缺失");
        }
        if(empty($param_arr["rcid"])){ 
            parent::echoFail ("参数rcid缺失");
        }
        $uid = $param_arr["uid"];
        $rcid = $param_arr["rcid"];
        $rno = empty($param_arr["rno"])?"----":$param_arr["rno"];
        $res = $this->ServiceModel->cancelderc($rcid); 
        if($res){
            //记录日志 
            $content["msg"]="取消房卡预扣";
            $content["rcid"]=$rcid;
            $content["rno"] = $rno;
            
            $data["LTYPE"]= SysDict::$LOGTYPE["cancelderc"];
            $data["AID"]= $uid;
            $data["CONTENT"]= json_encode($content);
            $this->LogModel->saveLog($data);  
            parent::echoSuccess(""); 
        }
        parent::echoFail ("操作执行失败");
    }
    //获取代理商的后台房卡
    //param={"uid":"11"}
    public function getbgrc(){
        if(empty($_REQUEST["param"])) { 
            parent::echoFail ("必要参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE); 
        if(empty($param_arr["uid"])){ 
            parent::echoFail ("参数uid缺失");
        }
        $uid = $param_arr["uid"]; 
        $num = $this->ServiceModel->getrcAgent($uid);
        $output["num"] = $num;
        parent::echoSuccess($output);
    }
    //获取房卡
    //param={"uid":"11"}
    public function getrc(){
        if(empty($_REQUEST["param"])) { 
            parent::echoFail ("必要参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE); 
        if(empty($param_arr["uid"])){ 
            parent::echoFail ("参数uid缺失");
        }
        $uid = $param_arr["uid"];
        $num = $this->ServiceModel->getrc($uid);
        $player = $this->ServiceModel->getUpperId($uid);
        $agent = $this->ServiceModel->getAgentByGID($uid);
        $paid = "";
        $wxid="";
        $name = ""; 
        $isagent = "0";//0 false;1 true
        if(count($agent)>0){//是代理商
            $agentRes = $agent[0];
            $paid = $agentRes["AID"]; 
            $isagent = "1";
        }
        foreach ($player as $val){
            $paid = empty($paid)?$val["PAID"]:$paid;
            $wxid = $val["WXID"]; 
            $name = $val["ANAME"];  
            break;
        }
        if($num>=0){
            $output["num"] = $num;
            $output["upperid"] = $paid;
            $output["wx"] = $wxid;
            $output["name"] = $name;
            $output["isagent"] = $isagent;
            parent::echoSuccess($output);
        }
        parent::echoFail ("数据有误");
    }
    
    //绑定代理商
    //param={"aid":"代理商邀请码","wx":"昵称","uid":"32位编码","uno":""}+uno
    public function bindagent(){
        if(empty($_REQUEST["param"])) { 
            parent::echoFail ("必要参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE); 
        if(empty($param_arr["uid"])){ 
            parent::echoFail ("参数uid缺失");
        }
        if(empty($param_arr["aid"])){ 
            parent::echoFail ("参数aid缺失");
        }
        if(empty($param_arr["wx"])){ 
            parent::echoFail ("参数wx缺失");
        }
//        if(empty($param_arr["uno"])){ 
//            parent::echoFail ("参数uno缺失");
//        }
        $gid = $param_arr["uid"];
        $wx = $param_arr["wx"];
        $aid = $param_arr["aid"];
        $uno = empty($param_arr["uno"])?"":$param_arr["uno"];
        $res = $this->ServiceModel->bindagent($gid,$wx,$aid,$uno);  
        
        if($res == "") {
            parent::echoFail ("代理商绑定失败");
        } 
        if($res == "-1"){ 
            parent::echoFail ("邀请码不存在");
        }
        parent::echoSuccess("");
    }
    
    //初始化房卡
    //param={"uid":"11","uno":"","name":"微信昵称","num":"5","t":"1-充值；2-赠送"}
    public function initRc(){
        if(empty($_REQUEST["param"])) { 
            parent::echoFail ("必要参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE); 
        if(empty($param_arr["uid"])){ 
            parent::echoFail ("参数uid缺失");
        }
        if(empty($param_arr["num"])){ 
            parent::echoFail ("参数num缺失");
        }
        if(empty($param_arr["t"])){
            parent::echoFail ("参数t缺失");
        }
        $num = $param_arr["num"];
        $gid = $param_arr["uid"]; 
        $type = $param_arr["t"]; 
        $uno = isset($param_arr["uno"])?$param_arr["uno"]:"";
        $wx = isset($param_arr["name"])?$param_arr["name"]:"";
        if($type!="1" && $type!="2"){
            parent::echoFail ("参数t错误");
        } 
        //查询这个人是否存在
        $user = $this->ServiceModel->getPlayerById($gid);
        $pid = $user["PID"];
        $paid = $user["PAID"];
        if($pid==""){
            $pid = $this->ServiceModel->addPlayer($gid,$wx,0,$uno);
        }
        $msg="";
        $ltype = "";
        if($type=="1"){
            $msg="玩家充值";
            $ltype = SysDict::$LOGTYPE["gamerpay"];
        }else if($type=="2"){
            $msg="完成任务，赠送玩家";
            $ltype = SysDict::$LOGTYPE["giverc"];
        }
        if($pid!=""){
            //创建房卡
            $rcres = $this->RoomcardModel->saveRoomCard_player($pid,(int)$num,$msg,$ltype);
//            parent::echoFail ($ltype);
//            return;
            if($rcres){
                //记录日志
                $content["msg"]=$msg;
                $content["num"]=$num;
                $content["uid"]=$gid;
                $content["pid"]=$pid; 
                $content["paid"]=$paid; 
                $data["LTYPE"]= $ltype;
                $data["AID"]= $gid;
                $data["CONTENT"] = json_encode($content);
                $this->LogModel->saveLog($data);
                parent::echoSuccess(""); 
            }
        }
        parent::echoFail ("接口错误");
    }
    
    //获取公告
    public function news(){
        $res = $this->ServiceModel->getnews();
        $content = "";
        foreach ($res as $val) {
            $content = $val["CONTENT"];
        }
        $data["content"] = $content;
        return parent::echoSuccess($data);
    }
    
    //后付费房间，扣除代理商房卡，仅用于大赢家后付费模式
    //param={"uid":"c93474d051bc11e7abee6516561f727b","num":"2","rno":"123432"}
    public function dercAgent(){
        if(empty($_REQUEST["param"])) { 
            parent::echoFail ("必要参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE); 
        if(empty($param_arr["uid"])){ 
            parent::echoFail ("参数uid缺失");
        }
        if(empty($param_arr["num"])){ 
            parent::echoFail ("参数num缺失");
        }
        if(empty($param_arr["rno"])){ 
            parent::echoFail ("参数rno缺失");
        }
        $uid = $param_arr["uid"];
        $num = (int)$param_arr["num"];
        $rno = $param_arr["rno"];
        if($num<=0){ 
            parent::echoFail ("扣除的房卡数量必须大于0");
        }
        //查询房卡数量
        $agent = $this->ServiceModel->getPlayerByGID($uid);
        $count = 0;
        $aid = 0;
        
        if(count($agent)>0){
            $count = (int)$agent[0]["ROOMCARD"];
            $aid = $agent[0]["AID"]; 
        }else{
            parent::echoFail ("未找到代理商");
        } 
//        $count = (int)$this->ServiceModel->getAgentrcNumByGid($uid);
        if($count<-1000){
            parent::echoFail ("已欠款多余1000张，请充值之后再使用");
        }
        $nums=[2,3,4];
        if(!in_array($num, $nums)){
            parent::echoFail ("扣除的房卡数量不合规");
        }
        $miss = 0;
        if( $count<$num ){ 
            //房卡数量不足，生成相应的房卡数量，并进行扣除
            $miss = $count>0?(int)($num-$count):(int)$num;//缺少？张房卡 
            //生成？张房卡 
            $dores = $this->RoomcardModel->saveRoomCard($aid,0-$miss,"房卡不足","", SysDict::$LOGTYPE["winnerpay"]);
            if(!$dores)
                parent::echoFail ("房卡不足，生成失败"); 
        }
        //查询代理商下的房卡 
        $rcid = $this->ServiceModel->getAgentRctop($aid,$num); 
        //扣除代理商下的房卡
        $res = $this->ServiceModel->dercAgent($aid,$rcid,$num,$miss,SysDict::$EXUID["agent_cardpay"],
                SysDict::$LOGTYPE["winnerpay"],$rno,"代开房费大赢家支付");
        if($res){
            //记录日志  
            /*
            $id = StringUtil::orderid(23,"1000");
            $content["msg"]="扣除房卡";
            $content["aid"] = $aid;
            $content["rcid"]=$rcid;
            $content["rno"]=$rno;
            $content["num"]=$num;
            $content["orderid"]=$id;
            $content["type"]=  SysDict::$PAYTYPE["winnerpay"];//扣除方式：代理商代开大赢家后付费
            $content["ispay"] = 0;
            
            $data["LID"]=$res;
            $data["LTYPE"]= SysDict::$LOGTYPE["winnerpay"];
            $data["AID"]= $uid;
            $data["CONTENT"] = json_encode($content);
            $this->LogModel->saveLog($data);
            */
            $output["id"] = $res;//订单ID
            parent::echoSuccess($output);
        }
        parent::echoFail ("房卡扣除失败");
    }
    //支付成功之后，更新订单状态
    //param={"id":"11","num":"订单房卡数量"}
    public function paid(){
        if(empty($_REQUEST["param"])){
            parent::echoFail ("必要的参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE); 
        if(empty($param_arr["id"])){ 
            parent::echoFail ("参数uid缺失");
        }
        if(empty($param_arr["num"])){ 
            parent::echoFail ("参数num缺失");
        } 
        $id = $param_arr["id"]; 
        $num = $param_arr["num"];
        //更新收益明细表中的支付状态
        $res = $this->ServiceModel->updatePayState($id);
        if($res){
            //更新代理商的房卡信息
//            $r = $this->SetviceModel->updateRcnum($aid,$num);
            parent::echoSuccess("","订单状态更新成功");
        }
        parent::echoFail ("订单状态更新失败");
        /*
        $log = $this->ServiceModel->getOrderById($id);
        $contentStr="";
        if(count($log)==1){
            $contentStr = $log[0]["CONTENT"];
            $content = json_decode($contentStr,TRUE);
            $content["ispay"] = 1;
            $content["paytime"] = date("Y-m-d H:i:s", time());
            $aid = $content["aid"];
            $res = $this->ServiceModel->updatePayState($id,json_encode($content));
            if($res){
                //更新代理商的房卡信息
                $r = $this->SetviceModel->updateRcnum($aid,$num);
                parent::echoSuccess("","订单状态更新成功");
            }
            parent::echoFail ("订单状态更新失败");
        }
        */
    }
    
    //扣除代理商后台房卡
    //param={"uid":"11","num":"扣除的房卡数量","rno":"房间ID"}
    public function debgcard(){
         if(empty($_REQUEST["param"])) { 
            parent::echoFail ("必要参数缺失");
        }
        $param=$_REQUEST["param"]; 
        $param_arr = json_decode($param,TRUE); 
        if(empty($param_arr["uid"])){ 
            parent::echoFail ("参数uid缺失");
        }
        if(empty($param_arr["num"])){ 
            parent::echoFail ("参数num缺失");
        }
        if(empty($param_arr["rno"])){ 
            parent::echoFail ("参数rno缺失");
        }
        $uid = $param_arr["uid"];
        $num = (int)$param_arr["num"];
        $rno = $param_arr["rno"];
        if($num<=0){ 
            parent::echoFail ("扣除的房卡数量必须大于0");
        }
        //查询代理商的后台房卡
        $agent = $this->ServiceModel->getAgentByGID($uid);
        $count = 0;
        $aid = 0 ;
        if(count($agent)>0){
            $count = (int)$agent[0]["ROOMCARD"];
            $aid = $agent[0]["AID"]; 
        }else{
            parent::echoFail ("未找到代理商");
        }
        $miss = 0;
        if( $count<$num ){ 
//             parent::echoFail ("房卡不足");
            //房卡数量不足，生成相应的房卡数量，并进行扣除
            $miss = $count>0?(int)($num-$count):(int)$num;//缺少？张房卡 
            //生成？张房卡  
            $dores = $this->RoomcardModel->saveRoomCard($aid,0-$miss,"茶馆房卡不足","", SysDict::$LOGTYPE["teashoppay"]);
            if(!$dores)
                parent::echoFail ("房卡不足，生成失败"); 
        }
        
        
        //查询代理商下的房卡 
        $rcid = $this->ServiceModel->getAgentRctop($aid,$num);
        //扣除代理商下的房卡
        $res = $this->ServiceModel->dercAgent($aid,$rcid,$num,$miss,SysDict::$EXUID["tea_cardpay"],
                SysDict::$LOGTYPE["teashoppay"],$rno,"茶馆模式扣除代理商后台房卡");
        if($res){
            parent::echoSuccess("");
        }
        parent::echoFail ("房卡扣除失败");
    }
}
