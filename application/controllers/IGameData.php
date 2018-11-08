<?php
/* 
 * 接口
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");

class IGameData extends BaseC {

    public function __construct() {
        parent::__construct(); 
        $this->load->model("IGameDataModel");
    }
    //游戏数据统计
    public function getGameData(){ 
        //获取玩家上级ID
//        $gamers = $this->UserModel->getUpperId();
        $begindate = date("Y-m-d H:i:s", time());
        //默认查询昨天的数据
        $s = isset($_REQUEST["s"])?$_REQUEST["s"]:"";
        $e = isset($_REQUEST["e"])?$_REQUEST["e"]:""; 
        $stime = $s==""? date("Y-m-d",strtotime("-1 day"))." 00:00" : date("Y-m-d",strtotime($s))." 00:00:00";
        $etime = $e==""? date("Y-m-d",strtotime("-1 day"))." 23:59" : date("Y-m-d",strtotime($e))." 23:59:59"; 
        $postData = array ("uno" =>"","s"=>$stime,"e"=>$etime); 
        $gameData = parent::HttpPost("GameLog",$postData); 
        $data = json_decode($gameData,true);
        $rows = $data["result"];
        $total = count($rows);
        $successed = 0;
        $failed = 0; 
        $sql_param = []; 
        foreach ($rows as $row) {
            $game_content = json_decode($row["game_content"],true); 
            $rno = $game_content["rno"];
            $ctime = $row["ctime"];
            $uno = $row["uno"]; 
            $name = $row["name"];
            $score = $row["score"];
            $others = json_decode($row["game_users"],true);
            $game_users = "";
            foreach ($others as $user) { 
                if(empty($user))
                    continue; 
                if(!isset($user["name"]))
                    $user["name"]="无名";
                if(!isset($user["allScore"]))
                    $user["allScore"]="0";
                $game_users.=$user["name"].":".$user["allScore"]."；";
            }
            $rcount = 0;
            $juCount = $game_content["param"]["juCount"];
            $tno = "";
            $tname = "";
            if(isset($game_content["param"]["teahouse"])){
                $tno = $game_content["param"]["teahouse"]["tno"];
                $tname = $game_content["param"]["teahouse"]["tname"]; 
            }
            switch ($juCount){
                case 4:
                    $rcount = 2;
                    break;
                case 8:
                    $rcount = 3;
                    break;
                case 12:
                    $rcount = 4;
                    break;
            }
            $paytype = $game_content["param"]["payType"];
            $starttime = $game_content["ctime"];//开始时间
            $endtime = $row["ctime"];//结束时间
            $minute = floor((strtotime($endtime)-strtotime($starttime))%86400/60);//发生时长
            $roundCount = count($game_content["roundIds"]) - 1; //实际玩的局数 
            
            $this->IGameDataModel->deldata(date("Y-m-d", strtotime($ctime)),$rno,$uno);
            $sql_param = [StringUtil::uuid(), date("Y-m-d", strtotime($ctime)),$tno,$tname,$rno,"",$uno,$name,$score,$game_users,$rcount,$paytype,$starttime,$endtime,$minute,$juCount,$roundCount, date("Y-m-d H:i:s", time()),date("Y-m-d H:i:s", time())];
            $sql_res = $this->IGameDataModel->addData($sql_param);
            if(!$sql_res){
                $failed++;
                throw new \Exception("时间：".date("Y-m-d H:i:s", time())." - 生成统计数据出错");
            }else{
                $successed++;
            }
        }
        $msg = "【游戏数据统计】【".$begindate." - ".date("H:i:s", time()) ."】总计：".$total."条|成功：".$successed."条|失败：".$failed."条|日期区间：".$stime."-".$etime;
        $this->setLog($msg);
        echo $msg;
    }

    //茶馆数据统计
    public function thouseData(){
        $begindate = date("Y-m-d H:i:s", time());
        //默认查询昨天的数据
        $s = isset($_REQUEST["s"])?$_REQUEST["s"]:"";
        $e = isset($_REQUEST["e"])?$_REQUEST["e"]:""; 
        $stime = $s==""? date("Y-m-d",strtotime("-1 day"))." 00:00" : date("Y-m-d",strtotime($s))." 00:00:00";
        $etime = $e==""? date("Y-m-d",strtotime("-1 day"))." 23:59" : date("Y-m-d",strtotime($e))." 23:59:59"; 
        $postData = array ("s"=>$stime,"e"=>$etime); 
        $thData = parent::HttpPost("staugame",$postData); 
        $data = json_decode($thData,true);
        $rows = $data["result"];  
        $total = count($rows);
        $successed = 0;
        $failed = 0;  
        foreach ($rows as $row){  
            $id = StringUtil::uuid();
            $date = $row["date"];
            $year = date("Y", strtotime($date));
            $month = date("m", strtotime($date));
            $day = date("d", strtotime($date));
            $week = date("W",strtotime($date));
            $quarter = ceil($month/3);
            $tno = $row["tno"];
            $tname = $row["teaname"];
            $owner = $row["tuname"];
            $uno = $row["uno"];
            $uname = $row["username"];
            $create = $row["create"];
            $game = $row["game"];
            $bigwin = $row["bigwin"]; 
//            $actnum = $row["num"];//活跃人数
            $actnum = 0;
            $paynum = $row["fk"];
            $this->IGameDataModel->delTData($date,$uno,$tno);
            $sql_param = [$id,$date,$year,$month,$day,$week,$quarter,$tno,$tname,$owner,$uno,$uname,$create,$game,$bigwin,$actnum,$paynum,date("Y-m-d H:i:s", time())];
            $sql_res = $this->IGameDataModel->addTData($sql_param);
            if(!$sql_res){
                $failed++;
                throw new \Exception("时间：".date("Y-m-d H:i:s", time())." - 生成统计数据出错");
            }else{
                $successed++;
            }
        }
        $msg = "【茶馆数据统计】【".$begindate." - ".date("H:i:s", time()) ."】总计：".$total."条|成功：".$successed."条|失败：".$failed."条|日期区间：".$stime."-".$etime;
        $this->setLog($msg);
        echo $msg;
    }
    
    //运营数据统计
    public function ghostReport(){
        $begindate = date("Y-m-d H:i:s", time());
        //默认查询昨天的数据
        $s = isset($_REQUEST["s"])?$_REQUEST["s"]:"";
        $e = isset($_REQUEST["e"])?$_REQUEST["e"]:""; 
        $stime = $s==""? date("Y-m-d",strtotime("-1 day"))." 00:00" : date("Y-m-d",strtotime($s))." 00:00:00";
        $etime = $e==""? date("Y-m-d",strtotime("-1 day"))." 23:59" : date("Y-m-d",strtotime($e))." 23:59:59"; 
        $postData = array ("s"=>$stime,"e"=>$etime); 
        $thData = parent::HttpPost("ghostdata",$postData); 
        $data = json_decode($thData,true);
        $rows = $data["result"]; 
        $total = count($rows);
        $successed = 0;
        $failed = 0;  
        foreach ($rows as $row) {
            $id = StringUtil::uuid();
            $date = $row["date"];
            $year = date("Y", strtotime($date));
            $month = date("m", strtotime($date));
            $day = date("d", strtotime($date));
            $week = date("W",strtotime($date));
            $quarter = ceil($month/3);
            $ucount = $row["ucount"];
            $tcount = $row["tcount"];
            
            $this->IGameDataModel->delGhostData($date);
            $sql_param = [$id,$date,$year,$month,$day,$week,$quarter,$ucount,$tcount,date("Y-m-d H:i:s", time())];
            $sql_res = $this->IGameDataModel->addGhostData($sql_param);
            if(!$sql_res){
                $failed++;
                throw new \Exception("时间：".date("Y-m-d H:i:s", time())." - 生成统计数据出错");
            }else{
                $successed++;
            }
        }
        $msg = "【酒鬼棋牌数据统计】【".$begindate." - ".date("H:i:s", time()) ."】总计：".$total."条|成功：".$successed."条|失败：".$failed."条|日期区间：".$stime."-".$etime;
        $this->setLog($msg);
        $this->userData($s, $e);
        echo $msg;
    }
    public function userData($s="",$e=""){
        $begindate = date("Y-m-d H:i:s", time());
        //默认查询昨天的数据
        $stime = $s==""? date("Y-m-d",strtotime("-1 day"))." 00:00" : date("Y-m-d",strtotime($s))." 00:00:00";
        $etime = $e==""? date("Y-m-d",strtotime("-1 day"))." 23:59" : date("Y-m-d",strtotime($e))." 23:59:59"; 
        $postData = array ("s"=>$stime,"e"=>$etime); 
        $thData = parent::HttpPost("User",$postData); 
        $data = json_decode($thData,true);
        $rows = $data["result"]; 
        $total = count($rows);
        $successed = 0;
        $failed = 0;  
        foreach ($rows as $row) {
            $pid = StringUtil::uuid();
            $gid = $row["ID"];
            $uno = $row["UNO"];
            $wxid = $row["NAME"];
            $nickname = $row["NAME"];
            $lastlogin = $row["LASTLOGIN"];
            $ctime = $row["CTIME"];
            $state = 1;
            $paid = 0;
            $res = $this->IGameDataModel->getPlayerData($gid);
            $sql_res = false; 
            if(count($res)>0){ 
                $time = date("Y-m-d H:i:s", time());
                $sql_param = [$uno,$wxid,$nickname,$lastlogin,$time,$gid];
                $sql_res = $this->IGameDataModel->updatePlayerData($sql_param); 
            }else{
                $sql_param = [$pid,$gid,$uno,$wxid,$nickname,$lastlogin,$paid,$state,$ctime];
                $sql_res = $this->IGameDataModel->addPlayerData($sql_param);
            } 
            
            if(!$sql_res){
                $failed++;
                throw new \Exception("时间：".date("Y-m-d H:i:s", time())." - 生成统计数据出错");
            }else{
                $successed++;
            }
        }
        $msg = "【酒鬼棋牌注册用户数据同步】【".$begindate." - ".date("H:i:s", time()) ."】总计：".$total."条|成功：".$successed."条|失败：".$failed."条|日期区间：".$stime."-".$etime;
        $this->setLog($msg);
        echo $msg;
    }

    protected function setLog($data){
        $logfilename = APP_PATH_LOG.'/gamelog_'.date("Ym").'.log';
        $fopen = fopen($logfilename,   'a+'); 
        fputs($fopen,   $data."\r\n"); 
        fclose($fopen); 
    }
}
