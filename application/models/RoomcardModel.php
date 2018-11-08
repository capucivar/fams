<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 

class RoomcardModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    } 
    /**
     * 房卡充值
     */ 
    function saveRoomCard($owner,$num,$mark="",$area="",$type=20) {
        $DBData   = $this->load->database($this->dbName, TRUE); 
        $count=$num<0?0-$num:$num;  
        $sql = "INSERT INTO `AGENT_ROOMCARD` (`RCID`,`OWNER`,`STATE`,`CTIME`,`MTIME`,`MARK`,`AREA`,`TYPE` ) VALUES ";
        $sqlParam = [];
        for($i=0;$i<$count;$i++){
            if($i==$count-1)
                $sql .= " (?,?,?,?,?,?,?,? )";
            else
                $sql .= " (?,?,?,?,?,?,?,? ),";
            array_push($sqlParam, StringUtil::uuid(date("ymd")));
            array_push($sqlParam, $owner);
            array_push($sqlParam, 1);
            array_push($sqlParam, date("Y-m-d H:i:s", time()));
            array_push($sqlParam, date("Y-m-d H:i:s", time()));
            array_push($sqlParam, $mark);
            array_push($sqlParam, $area);
            array_push($sqlParam, $type); 
        }
        //修改总房卡数 
        $state = $owner==SysDict::$SYSTEM_AGENT?SysDict::$SYSTEM_AGENT_STATE:1;
        $sql2  = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`+? ,`MTIME`=? WHERE `AID`=? and STATE= ?"; 
        $sqlParam2 = [$num,date("Y-m-d H:i:s", time()),$owner,$state];
        
        //从平台扣除相应数量的房卡
        $sql3  = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`-? ,`MTIME`=? WHERE `AID`=? and STATE=? "; 
        $sqlParam3 = [$num,date("Y-m-d H:i:s", time()), SysDict::$SYSTEM_AGENT,  SysDict::$SYSTEM_AGENT_STATE]; 
        
        $DBData->trans_start();
        $DBData->query($sql, $sqlParam); 
        $DBData->query($sql2, $sqlParam2);
        if($owner!=SysDict::$SYSTEM_AGENT && 1!=1)//暂时不从平台扣除数量
            $DBData->query($sql3, $sqlParam3);
        $DBData->trans_complete(); 
        return $DBData->trans_status(); 
    }
    
    function saveRoomCard_bak($owner,$num,$mark="",$area="",$type=20) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $DBData->trans_start(); 
        $count=$num<0?0-$num:$num;  
        for($i=0;$i<$count;$i++){
            $sql = "INSERT INTO `AGENT_ROOMCARD` (`RCID`,`OWNER`,`STATE`,`CTIME`,`MTIME`,`MARK`,`AREA`,`TYPE` )VALUES (?,?,?,?,?,?,?,? )";
            $sqlParam = array(
                StringUtil::uuid(),
                $owner,
                1,  
                date("Y-m-d H:i:s", time()),
                date("Y-m-d H:i:s", time()),
                $mark,
                $area,
                $type);
            $DBData->query($sql, $sqlParam); 
        }
        //修改总房卡数 
        $sql2  = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`+? ,`MTIME`=? WHERE `AID`=? and STATE=1 "; 
        $sqlParam2 = [$num,date("Y-m-d H:i:s", time()),$owner ]; 
        $DBData->query($sql2, $sqlParam2);
        
        //从平台扣除相应数量的房卡
        $sql3  = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`-? ,`MTIME`=? WHERE `AID`=999999 and STATE=9 "; 
        $sqlParam3 = [$num,date("Y-m-d H:i:s", time())]; 
        $DBData->query($sql3, $sqlParam3);
        
        $DBData->trans_complete(); 
        return $DBData->trans_status(); 
    }
    /**
     * 玩家充值
     */
    function saveRoomCard_player($owner,$num,$mark="",$type=0) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        //查询这个玩家是否存在
        $DBData->trans_start(); 
        $sql = "INSERT INTO `AGENT_ROOMCARD` (`RCID`,`OWNER`,`STATE`,`CTIME`,`MTIME`,`MARK`,`AREA`,`TYPE` )VALUES ";
        $sqlParam = [];
        $rcid="";
        for($i=0;$i<$num;$i++){
//            $sql = "INSERT INTO `AGENT_ROOMCARD` (`RCID`,`OWNER`,`STATE`,`CTIME`,`MTIME`,`MARK`,`AREA`,`TYPE` )VALUES (?,?,?,?,?,?,?,?)";
            if($i==$num-1)
                $sql.=" (?,?,1,?,?,?,'',?) ";
            else
                $sql.="(?,?,1,?,?,?,'',?),";
            $id = StringUtil::uuid(date("ymd"));
            $rcid.=empty($rcid)?$id:",".$id;
            array_push($sqlParam, $id);
            array_push($sqlParam, $owner); 
            array_push($sqlParam, date("Y-m-d H:i:s", time()));
            array_push($sqlParam, date("Y-m-d H:i:s", time())); 
            array_push($sqlParam, $mark); 
            array_push($sqlParam, $type);
        }
        $DBData->query($sql, $sqlParam);
        //修改总房卡数
        $sql2  = " UPDATE `AGENT_PLAYER` SET `ROOMCARD`=`ROOMCARD`+? ,`MTIME`=? WHERE `PID`=? and STATE=1 ";
        $sqlParam2 = [$num,date("Y-m-d H:i:s", time()),$owner ]; 
        $DBData->query($sql2, $sqlParam2);
        
        //从平台扣除相应数量的房卡
//        $sql3  = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`-? ,`MTIME`=? WHERE `AID`=999999 and STATE=9 "; 
//        $sqlParam3 = [$num,date("Y-m-d H:i:s", time())]; 
//        $DBData->query($sql3, $sqlParam3);

        if($type==SysDict::$LOGTYPE["giverc"]){//玩家领取赠送房卡
            //生成平台赠送的记录以及获取赠送记录
            $sql4  = " INSERT INTO AGENT_ROOMCARD_LOG (ID,TYPE,NUM,OPERATOR,AID,TOAID,MONEY,ACTUALPAY,RCID,MARK,CTIME) VALUES (?,?,?,?,?,?,0,0,?,?,?),(?,?,?,?,?,?,0,0,?,?,?) ";
            $sqlParam4 = [
                //平台记录
                StringUtil::orderid(23, SysDict::$EXUID["sys_give_card"]),
                SysDict::$LOGTYPE["sys_give_card"],
                0-$num,
                SysDict::$SYSTEM_AGENT,
                SysDict::$SYSTEM_AGENT, 
                $owner, 
                $rcid,
                "玩家完成任务，领取赠送房卡", 
                date("Y-m-d H:i:s", time()),
                //玩家记录
                StringUtil::orderid(23, SysDict::$EXUID["get_sys_give_card"]),
                SysDict::$LOGTYPE["get_sys_give_card"],
                $num,
                SysDict::$SYSTEM_AGENT,
                $owner, 
                SysDict::$SYSTEM_AGENT,
                $rcid,
                "玩家完成任务，领取赠送房卡", 
                date("Y-m-d H:i:s", time())
            ]; 
            $DBData->query($sql4, $sqlParam4);
        }else if($type==SysDict::$LOGTYPE["gamerpay"]){
            //生成平台赠送的记录以及获取赠送记录
            $sql4  = " INSERT INTO AGENT_ROOMCARD_LOG (ID,TYPE,NUM,OPERATOR,AID,TOAID,MONEY,ACTUALPAY,RCID,MARK,CTIME) VALUES (?,?,?,?,?,?,0,0,?,?,?)";
            $sqlParam4 = [
                //玩家充值记录
                StringUtil::orderid(23, SysDict::$EXUID["player_rcharge_card"]),
                SysDict::$LOGTYPE["gamerpay"],
                $num,
                $owner,
                $owner, 
                $owner,
                $rcid,
                "玩家充值", 
                date("Y-m-d H:i:s", time())
            ]; 
            $DBData->query($sql4, $sqlParam4);
        }
        
        $DBData->trans_complete(); 
        return $DBData->trans_status(); 
    }
    /**
     * 转让房卡
     */
    function transferRCard($num,$aid, $paid) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql  = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`+? ,`MTIME`=? WHERE `AID`=? AND `PAID` = ? and STATE=1 ";
        $sql2 = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`-? ,`MTIME`=? WHERE `AID`=?  and STATE=1 ";
        $sqlParam = [$num,date("Y-m-d H:i:s", time()),$aid, $paid ]; 
        $sqlParam2 = [$num,date("Y-m-d H:i:s", time()),$paid ]; 
        $DBData->trans_start(); 
        $DBData->query($sql, $sqlParam);
        $DBData->query($sql2, $sqlParam2);
        $DBData->trans_complete(); 
        return $DBData->trans_status();
    }
    /*
     * 获取房卡列表
     */
    function getRclist($level){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "
                SELECT T.* 
                FROM
                    `AGENT` T
                INNER JOIN `AGENT_TOKEN` AT ON T.AID=AT.AID
                WHERE 
                    AT.TOKEN=? AND T.STATE=1 AND AT.STATE=1 ";
        $query  = $DBData->query($sql, [$token]);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return "";
    }
    function getList($level){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "
                SELECT T.* 
                FROM
                    `AGENT` T 
                WHERE 
                    T.STATE=1 AND T.LEVEL= ? ";
        $query  = $DBData->query($sql, [$level]);
        if ($query->num_rows() > 0) {
            return $query->result_array(); 
        }
        return "";
    }
    
    //管理员/总代查询房卡记录
    function getRclog($stime="",$etime="",$level=""){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql=" SELECT
                    B.ANAME,
                    B.`LEVEL`,
                    C.UNO,
                    C.WXID,
                    C.PAID,
                    A.*
            FROM
                    AGENT_ROOMCARD_LOG AS A
            LEFT JOIN AGENT B ON A.AID = B.AID
            LEFT JOIN AGENT_PLAYER C ON A.AID = C.PID
            WHERE
                    1 = 1";
        $sqlParam=[];
        if(!empty($stime) && !empty($etime)){
            $sql.= " AND A.CTIME BETWEEN ? AND ?";
            array_push($sqlParam, $stime);
            array_push($sqlParam, $etime);
        } 
        if($level==SysDict::$ANGENTLEVEL["player"]){
            $sql.= " AND (B.LEVEL IS NULL OR B.LEVEL = '')";
        }else if(!empty($level)){
            $sql.= " AND B.LEVEL = ?";
            array_push($sqlParam, $level); 
        }
        $sql.="  ORDER BY A.CTIME DESC";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array(); 
    }
    
    function getRclog_bak($stime="",$etime=""){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT
                    *
            FROM
                    AGENT_LOG
            WHERE
                    LTYPE IN (20, 21, 22, 23, 24) ";
        $sqlParam=[];
        if(!empty($stime) && !empty($etime)){
            $sql.= " AND CTIME BETWEEN ? AND ?";
            array_push($sqlParam, $stime);
            array_push($sqlParam, $etime);
        }
        $sql.="  ORDER BY CTIME DESC";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array(); 
    }
}
