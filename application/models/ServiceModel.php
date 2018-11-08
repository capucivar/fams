<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 
include_once(APP_PATH_L . "SysDict.php"); 

class ServiceModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    }
    function prederc($id,$num){
        $rcid = $this->getrctop($id,$num); 
        if($rcid=="")
            return "";
        $DBData = $this->load->database($this->dbName, TRUE); 
        $sql = "UPDATE AGENT_ROOMCARD SET STATE=2,MTIME=? WHERE STATE = 1 AND OWNER IN "
                . "(SELECT PID FROM AGENT_PLAYER WHERE GID=?) AND RCID in( ".$rcid." )";
        $sqlParam = array(
            date("Y-m-d H:i:s", time()),
            $id); 
        $DBData->query($sql, $sqlParam); 
        if($DBData->affected_rows() > 0)
            return $rcid;
        return "";
    }
    function derc($uid,$rcid,$num,$rno="",$pid=""){
//        $num = $this->getrc($uid);  
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE AGENT_ROOMCARD SET STATE=3,MTIME=? WHERE STATE = 2 AND RCID in(".$rcid.")";
        $sql2 = " UPDATE `AGENT_PLAYER` SET `ROOMCARD`= ROOMCARD-? ,`MTIME`=? WHERE `GID`=?  and STATE=1";
        $sqlParam = array(date("Y-m-d H:i:s", time())); 
        $sqlParam2 = array((int)$num,date("Y-m-d H:i:s", time()),$uid); 
        
        //平台回收房卡
//        $sql3  = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`+? ,`MTIME`=? WHERE `AID`=999999 and STATE=9 "; 
//        $sqlParam3 = [$num,date("Y-m-d H:i:s", time())]; 
//        $DBData->query($sql3, $sqlParam3);
        
        //创建一条消费记录
        $sql4 = "INSERT INTO AGENT_ROOMCARD_LOG (ID,TYPE,NUM,OPERATOR,AID,TOAID,MONEY,ACTUALPAY,RCID,MARK,CTIME,RNO) VALUES (?,?,?,?,?,?,0,0,?,?,?,?)";
        $sqlParam4 = [
            //玩家消费记录
            StringUtil::orderid(23, SysDict::$EXUID["cardpay"]),
            SysDict::$LOGTYPE["derc"],
            0-$num,
            $pid,
            $pid, 
            $pid,
            $rcid,
            "房卡消费", 
            date("Y-m-d H:i:s", time()),
            $rno
        ]; 
        
        $DBData->trans_start(); 
        $DBData->query($sql, $sqlParam);
        $DBData->query($sql2, $sqlParam2);
        $DBData->query($sql4, $sqlParam4);
        $DBData->trans_complete(); 
        return $DBData->trans_status(); 
    }
    
    //取消扣房卡
    function cancelderc($rcid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE AGENT_ROOMCARD SET STATE=1,MTIME=? WHERE RCID in (".$rcid.")"; 
        $sqlParam = array(date("Y-m-d H:i:s", time())); 
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() > 0;
    }
    function getrc($id,$state=1){ 
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT
                        COUNT(*) AS ROOMCARD
                FROM
                        AGENT_ROOMCARD AS A
                INNER JOIN AGENT_PLAYER AS B ON A.`OWNER` = B.PID
                WHERE
                        A.STATE = ?
                AND B.GID = ?";
        $query  = $DBData->query($sql, [$state,$id]); 
        $arr = $query->result_array();
        $num = 0;
        foreach ($arr as $val) {
            $num = $val["ROOMCARD"];
            return $num; 
        }
        return -1;
    }
    
    function getrcAgent($id,$state=1){ 
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT
                        COUNT(*) AS ROOMCARD
                FROM
                        AGENT_ROOMCARD AS A
                INNER JOIN AGENT AS B ON A.`OWNER` = B.AID
                WHERE
                        A.STATE = ?
                AND B.GAMEID = ?";
        $query  = $DBData->query($sql, [$state,$id]); 
        $arr = $query->result_array();
        $num = 0;
        foreach ($arr as $val) {
            $num = $val["ROOMCARD"];
            return $num; 
        }
        return -1;
    }
    
    function getrctop($id,$num){ 
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT
                    A.RCID
            FROM
                    AGENT_ROOMCARD AS A
            INNER JOIN AGENT_PLAYER AS B ON A.`OWNER` = B.PID
            WHERE
                    A.STATE = 1
            AND B.GID = ?
            ORDER BY
                    A.CTIME
            LIMIT 0,?";
        $query  = $DBData->query($sql, [$id,(int)$num]); 
        $arr = $query->result_array();
        $rcid = "";
        foreach ($arr as $val) {
            $rcid .= $rcid==""?$val["RCID"]:",".$val["RCID"]; 
        }
        return $rcid;
    }
    //获取代理商房卡
    function getAgentRctop($id,$num){ 
        $DBData = $this->load->database($this->dbName, TRUE);
         $sql    = "SELECT
                       RCID
                FROM
                        AGENT_ROOMCARD  
                WHERE
                        OWNER = $id AND STATE=1
                ORDER BY
                        CTIME
                LIMIT 0,$num";
        $query  = $DBData->query($sql); 
        $arr = $query->result_array();
        $rcid = "";
        foreach ($arr as $val) {
            $rcid .= $rcid==""?$val["RCID"]:",".$val["RCID"]; 
        }
        return $rcid;
    }
    //扣除代理商的房卡 num2：房卡不足，已经预充值的房卡
    function dercAgent($aid,$rcid,$num,$num2,$ex_rclog,$type_rclog,$rno="",$msg="" ){ 
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE AGENT_ROOMCARD SET STATE=3,MTIME=? WHERE STATE = 1 AND RCID in(".$rcid.")";
        $sql2 = "UPDATE `AGENT` SET `ROOMCARD`=ROOMCARD-? ,`MTIME`=? WHERE `AID`=?  and STATE=1";
        $sqlParam = array(date("Y-m-d H:i:s", time())); 
        $rc = $num - $num2;
        $sqlParam2 = array((int)$rc,date("Y-m-d H:i:s", time()),$aid); 
        
        //平台回收房卡
//        $sql3  = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`+? ,`MTIME`=? WHERE `AID`=999999 and STATE=9 "; 
//        $sqlParam3 = [$num,date("Y-m-d H:i:s", time())]; 
         
        //计算代理商元宝收益
        $gold = SysDict::GET_GOLD_NUM((int)$num); 
        $sql4  = " UPDATE `AGENT` SET `GOLD`=`GOLD`+? ,`MTIME`=?  WHERE `AID`=?  and STATE=1";
        $sqlParam4 = [$gold,date("Y-m-d H:i:s", time()),$aid]; 
        
        $sql41  = " UPDATE `AGENT` SET `GOLD`=`GOLD`-? ,`MTIME`=?  WHERE `AID`=?  and STATE=?";
        $sqlParam41 = [$gold,date("Y-m-d H:i:s", time()),  SysDict::$SYSTEM_AGENT,SysDict::$SYSTEM_AGENT_STATE ]; 
        
        //添加一条代理商的元宝收益记录//添加一条平台支出的元宝记录
        $sql5 = "INSERT INTO AGENT_GOLD_LOG (ID,AID,TYPE,NUM,CONTENT,RNO,CARDNUM,ISPAY,CTIME,ISVALID) VALUES (?,?,?,?,?,?,?,?,?,1),(?,?,?,?,?,?,?,?,?,1)";
//        $logid = StringUtil::orderid(23,SysDict::$EXUID["goldearn"]);
        $sqlParam5 = [
            StringUtil::orderid(23,SysDict::$EXUID["goldearn"]),
            $aid, 
            SysDict::$LOGTYPE["goldearn"],
            $gold,
            $msg."，元宝收益",
            $rno,
            $num,
            0,
            date("Y-m-d H:i:s", time()),
            
            StringUtil::orderid(23,  SysDict::$EXUID["sys_gold_pay"]),
            SysDict::$SYSTEM_AGENT,
            SysDict::$LOGTYPE["sys_gold_pay"],
            0-$gold,
            "平台支出",
            $rno,
            $num,
            0,
            date("Y-m-d H:i:s", time())
            ];
         
        //创建一条房卡消费记录
        $sql6 = "INSERT INTO AGENT_ROOMCARD_LOG (ID,TYPE,NUM,OPERATOR,AID,TOAID,MONEY,ACTUALPAY,RCID,MARK,CTIME,RNO) VALUES (?,?,?,?,?,?,0,0,?,?,?,?)";
        $logid = StringUtil::orderid(23, $ex_rclog);
        $sqlParam6 = [
            //玩家消费记录
            $logid,
            $type_rclog,
            0-$num,
            $aid,
            $aid, 
            $aid,
            $rcid,
            $msg, 
            date("Y-m-d H:i:s", time()),
            $rno
        ]; 
        
        $DBData->trans_start(); 
        $DBData->query($sql, $sqlParam);
        $DBData->query($sql2, $sqlParam2);
//        $DBData->query($sql3, $sqlParam3);
        $DBData->query($sql4, $sqlParam4);
        $DBData->query($sql41, $sqlParam41);
        $DBData->query($sql5, $sqlParam5);
        $DBData->query($sql6, $sqlParam6);
        
        $DBData->trans_complete(); 
        $res = $DBData->trans_status();  
        if($res)
            return $logid;
        return 0;
    }
   
    function bindagent($gid,$wx,$sharecode,$uno){ 
        $agent = $this->isExist($sharecode);
        if(count($agent)<1) {
            return "-1";
        }
        $paid = $agent["AID"];
        $user = $this->getPlayerById($gid);
        if($user["PID"]==""){
            //添加
            return $this->addPlayer($gid, $wx, $paid,$uno);
        }else{
            //修改
            return $this->updatePlayer($gid,$wx, $paid,$uno);
        }
        return "";
    }
    //return PID
    function getPlayerById($gid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = " SELECT PID,PAID FROM `AGENT_PLAYER` WHERE STATE=1 AND GID=? ";
        $query  = $DBData->query($sql, [$gid]);
        return $query->row_array();
    }
    //获取玩家的上级代理商ID，只查询玩家代理商
    function getUpperId($gid){ 
        $DBData = $this->load->database($this->dbName, TRUE); 
        $sql = "SELECT A.PAID,B.WXID,B.ANAME FROM `AGENT_PLAYER` AS A LEFT JOIN AGENT AS B ON A.PAID=B.AID WHERE A.STATE=1 AND A.GID=? AND B.`LEVEL`=3";
        $query  = $DBData->query($sql, [$gid]);
        return $query->result_array();
    }
    function getAgentByGID($gid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM `AGENT` WHERE GAMEID = ? AND STATE=1 ";
        $sqlParam = array(
            $gid
        );
        $query  = $DBData->query($sql, $sqlParam);  
        return $query->result_array();
    }
    function getPlayerByGID($gid){
        $DBData = $this->load->database($this->dbName, TRUE);
//        $sql = "SELECT * FROM `AGENT` WHERE GAMEID = ? AND STATE=1 ";
        $sql = "SELECT
                        B.*
                FROM
                        `AGENT_PLAYER` AS A
                INNER JOIN `AGENT` AS B ON A.PAID = B.AID
                WHERE
                        A.GID = ?
                AND B.STATE = 1";
        $sqlParam = array(
            $gid
        );
        $query  = $DBData->query($sql, $sqlParam);  
        return $query->result_array();
    }
    function isExist($sharecode){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM `AGENT` WHERE SHARECODE=? AND STATE=1  AND ( INVALIDATE IS NULL OR INVALIDATE='' OR DATE_FORMAT(INVALIDATE,'%Y-%m-%d %H:%i:%S')<?)";
        $sqlParam = array(
            $sharecode,
            date("Y-m-d H:i:s",  time())
        );
        $query  = $DBData->query($sql, $sqlParam);
        return $query->row_array();
//        return $query->num_rows()>0 ;
    }
    function addPlayer($gid,$wx,$paid,$uno){  
        $DBData = $this->load->database($this->dbName, TRUE); 
        $uuid = StringUtil::uuid();
        $sql      = "INSERT INTO `AGENT_PLAYER` (
                                `PID`, 
                                `GID`,
                                `UNO`,
                                `WXID`, 
                                `NICKNAME`,
                                `CELLPHONE`,
                                `PAID`,
                                `REMARK`,
                                `CTIME`,
                                `MTIME` )
                       VALUES (?,?,?,?,?,?,?,?,?,?)";
        $sqlParam = array(
            $uuid,
            $gid, 
            $uno, 
            "",
            $wx,
            "",
            $paid,
            "", 
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()) 
        );
        $DBData->query($sql, $sqlParam);
        if($DBData->affected_rows() == 1)
            return $uuid;
        return "";
    }
    function updatePlayer($gid,$wx,$paid,$uno){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT_PLAYER` 
            SET 
                `NICKNAME` = ?,
                `PAID` = ?, 
                `UNO` = ?,
                `MTIME`=?
            WHERE `GID`=?";
        $sqlParam = [
            $wx,
            $paid,
            $uno,
            date("Y-m-d H:i:s", time()),
            $gid
        ];
        $DBData->query($sql, $sqlParam);
        if($DBData->affected_rows() == 1)
            return "1";
        return "";
    }
    function getnews(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_NOTICE WHERE STATE = 1 ORDER BY CTIME DESC LIMIT 1";
        $query  = $DBData->query($sql);  
        return $query->result_array();
    }
    //更新订单状态，其实就是修改log表中的content
    function updatePayState_bak($id,$data){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT_LOG` 
            SET 
                `CONTENT` = ?
            WHERE `LID`=?";
        $sqlParam = [
            $data, 
            $id
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows(); 
    }
    //修改元宝记录表中的支付状态
    function updatePayState($id){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT_GOLD_LOG` 
            SET 
                `ISPAY` = ?,
                `PAYTIME` = ?
            WHERE `ID`= ? ";
        $sqlParam = [
            1, 
            date("Y-m-d H:i:s", time()),
            $id
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows(); 
    }
    
    function getOrderById($id){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_LOG WHERE LID = ?";
        $query  = $DBData->query($sql,[$id]);  
        return $query->result_array();
    }
    function updateRcnum($aid,$num){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE AGENT SET ROOMCARD=?,MTIME=? WHERE AID=? AND STATE=1 ";
        $sqlParam = array($num,date("Y-m-d H:i:s", time()),$aid);
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    } 
}
