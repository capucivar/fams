<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php");  

class AgentModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct(); 
    }

    /**
     * 验证账号密码是否正确
     */
    function isLoginValid($aid, $pwd) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "SELECT * FROM `AGENT` WHERE (AID=? OR ANAME=?) AND PASSWORD=? AND STATE=1";
        $sqlParam = array(
           $aid,
           $aid,
           $pwd ); 
        $query    = $DBData->query($sql, $sqlParam);
        return $query->num_rows() > 0;
    }
    /**
     * 修改最后登录时间
     */
    function updateLastLogin($aid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE AGENT SET LASTLOGIN=? WHERE AID=? ";
        $sqlParam = array(date("Y-m-d H:i:s", time()),$aid);
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    /**
     * 获取登录 token
     */
    function getToken($aid) {
        $db        = $this->load->database($this->dbName, TRUE);
        $sqlDelete = "DELETE FROM AGENT_TOKEN WHERE AID=?";
        $sqlInsert = "INSERT INTO AGENT_TOKEN (ID, `TOKEN`, AID, CTIME, MTIME ) VALUES (?,?,?,SYSDATE(),SYSDATE())";

        $db->trans_start();
        
        // 删除旧数据
        $db->query($sqlDelete, [$aid]);

        // 插入新数据
        $id = StringUtil::uuid();
        $db->query($sqlInsert, [$id, $id, $aid]);

        $db->trans_complete();

        if ($db->trans_status()) {
            return $id;
        }
        return "";
    }

    /**
     * 插入一条代理商基本信息
     * $isbind = true,检查是否已经绑定过代理商，如果绑定则修改代理商ID
     */
    function saveAgentInfo($param,$isbind=false) { 
        $DBData   = $this->load->database($this->dbName, TRUE);
        $aid = StringUtil::randStr(6,"NUMBER");
        $sql      = "INSERT INTO `AGENT` (
                                `AID`,
                                `ANAME`,
                                `LEVEL`,
                                `PAID`,
                                `PASSWORD`,
                                `WXID`, 
                                `WXNICKNAME`, 
                                `GAMEID`,
                                `UNO`,
                                `CELLPHONE`,
                                `ROOMCARD`,
                                `GOLD`,
                                `AREA`,
                                `MARK`,
                                `SHARECODE`,
                                `SOURCE`,
                                `CTIME`,
                                `MTIME` )
                       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $sqlParam = array( 
            $aid,
            $param["ANAME"],
            $param["LEVEL"],
            $param["PAID"],
            "888888",//pwd 
            $param["WXID"],
            $param["WXNICKNAME"],
            $param["GAMEID"],
            $param["UNO"],
            $param["CELLPHONE"],
            $param["ROOMCARD"],
            $param["GOLD"],
            $param["areaProvince"] . "," . $param["areaCity"] . "," . $param["areaTown"],
            $param["MARK"],
            StringUtil::randStr(6,"NUMBER"),
            $param["SOURCE"],
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()) 
        );
        $DBData->query($sql, $sqlParam); 
        if($DBData->affected_rows() == 1){ 
            if($isbind){
                $pid = $this->getPlayerByGId($param["GAMEID"]); 
                if($pid==""){
                    $uparam["GID"] = $param["GAMEID"];
                    $uparam["UNO"] = $param["UNO"];
                    $uparam["WXID"] = $param["WXID"];
                    $uparam["NICKNAME"] = $param["WXNICKNAME"];
                    $uparam["CELLPHONE"] = $param["CELLPHONE"];
                    $uparam["PAID"] = $aid;
                    $uparam["REMARK"] = ""; 
                    $this->saveUserInfo($uparam);
                }else{
                    $this->updateUserPaid($aid,$param["GAMEID"],$param["UNO"]);
                }
            }
            return $aid; 
        }
        return ""; 
    }
    function saveUserInfo($param) {
        $DBData   = $this->load->database($this->dbName, TRUE);
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
            StringUtil::uuid(),
            $param["GID"], 
            $param["UNO"], 
            $param["WXID"],
            $param["NICKNAME"],
            $param["CELLPHONE"],
            $param["PAID"],
            $param["REMARK"], 
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()) 
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() ;
    }
    //根据GID获取玩家信息
    function getPlayerByGId($gid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = " SELECT PID FROM `AGENT_PLAYER` WHERE STATE=1 AND GID=? ";
        $query  = $DBData->query($sql, [$gid]);
        $arr = $query->result_array();
        $pid = "";
        foreach ($arr as $val) {
            $pid = $val["PID"]; 
            return $pid;
        }
        return $pid;
    }
    //修改玩家的绑定关系
    function updateUserPaid($aid,$gid,$uno) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT_PLAYER` 
            SET 
                `PAID` = ?, 
                `UNO` = ?,
                `MTIME`=?
            WHERE `GID`=?";
        $sqlParam = [
            $aid,
            $uno,
            date("Y-m-d H:i:s", time()),
            $gid
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    /**
     * 更新代理商基本信息
     */
    function updateAgentInfo($param) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT` 
            SET 
                `ANAME`=?,
                `WXID`=?,
                `CELLPHONE`=?, 
                `AREA`=?,
                `MARK`=?,
                `MTIME`=?
            WHERE `AID`=?";
        $sqlParam = [
            $param["ANAME"],
            $param["WXID"],
            $param["CELLPHONE"], 
            $param["areaProvince"] . "," . $param["areaCity"] . "," . $param["areaTown"],
            $param["MARK"],
            date("Y-m-d H:i:s", time()),
            $param["AID"],
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    /**
     * 修改备注信息
     */
    function updateMark($mark,$aid) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT` 
            SET 
                `MARK`=?, 
                `MTIME`=?
            WHERE `AID`=?";
        $sqlParam = [
            $mark,
            date("Y-m-d H:i:s", time()),
            $aid,
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    /**
     * 修改手机号
     */
    function updatePnum($newPhone,$aid) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT` 
            SET 
                `CELLPHONE`=?, 
                `MTIME`=?
            WHERE `AID`=?";
        $sqlParam = [
            $newPhone,
            date("Y-m-d H:i:s", time()),
            $aid,
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    /**
     * 修改有效期
     */
    function updateValidate($date,$aid,$state=1) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT` 
            SET 
                `STATE`=?,
                `INVALIDATE`=?, 
                `MTIME`=?
            WHERE `AID`=?";
        $sqlParam = [
            $state,
            $date,
            date("Y-m-d H:i:s", time()),
            $aid,
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    /**
     * 删除代理商信息
     */
    function delAgent($aid, $paid) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
                     UPDATE 
                        `AGENT` 
                     SET `STATE`=0 ,`MTIME`=? 
                     WHERE `AID`=? AND `PAID` = ?
                     ";
        $sqlParam = [
            date("Y-m-d H:i:s", time()),
            $aid,
            $paid
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    /**
     * 转让房卡
     */
    function transferRCard($num,$aid, $paid,$level) {
        $DBData = $this->load->database($this->dbName, TRUE);
        //获取随机num张房卡数据
        $getSql = "SELECT RCID FROM AGENT_ROOMCARD WHERE `OWNER`=? ORDER BY CTIME LIMIT ?";
        $getQuery  = $DBData->query($getSql, [$paid,$num]);
        if ($getQuery->num_rows() < $num) { 
            return false;
        }
        $arr = $getQuery->result_array();
        $ids = "";
        foreach ($arr as $val) {
            $rcid = $val["RCID"];
            $ids.=$ids==""?$rcid:",".$rcid;
        } 
        $sql  = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`+? ,`MTIME`=? WHERE `AID`=? and STATE=1 ";
        $sqlParam = [$num,date("Y-m-d H:i:s", time()),$aid ]; 
        $state = $paid== SysDict::$SYSTEM_AGENT?SysDict::$SYSTEM_AGENT_LEVEL:1;
        $sql2 = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`-? ,`MTIME`=? WHERE `AID`=?  and STATE=? "; 
        $sqlParam2 = [$num,date("Y-m-d H:i:s", time()),$paid,$state]; 
        
        $sql3 = "UPDATE AGENT_ROOMCARD SET `OWNER` = ?,`LEVEL` = ?,`MTIME`=? WHERE RCID IN (".$ids.")";
        $sqlParam3 = [$aid,$level,date("Y-m-d H:i:s", time())];
        
        $DBData->trans_start(); 
        $DBData->query($sql, $sqlParam);
        $DBData->query($sql2, $sqlParam2);
        $DBData->query($sql3, $sqlParam3);
        $DBData->trans_complete(); 
        $res = $DBData->trans_status();
        return $res?$ids:$res; 
//        return $DBData->trans_status();
    }
    /**
     * 验证手机号与验证码是否匹配
     */
    function isValidNumValid($cellPhone, $validNum) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "SELECT id FROM `phone_valid` WHERE cellphone=? AND validnum=? AND ctime>? AND isvalid=1";
        $sqlParam = array(
            $cellPhone,
            $validNum,
            date("Y-m-d H:i:s", time() - 5 * 60));
        $query    = $DBData->query($sql, $sqlParam);
        return $query->num_rows() > 0;
    }

    /**
     * 验证手机号是否存在
     */
    function isMobileExist($mobile,$aid="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT AID FROM `AGENT` WHERE CELLPHONE=? AND STATE=1";
        if(!empty($aid)){
            $sql.=" AND AID<>".$aid;
        }
        $query  = $DBData->query($sql, [$mobile]);
        return $query->num_rows() > 0;
    }
    /**
     * 验证手机号是否正确
     */
    function isMobileOK($mobile,$aid="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT AID FROM `AGENT` WHERE CELLPHONE=? AND STATE=1";
        if(!empty($aid)){
            $sql.=" AND AID=".$aid;
        }
        $query  = $DBData->query($sql, [$mobile]);
        return $query->num_rows() > 0;
    }
     /**
     * 验证微信号是否存在
     */
    function isWXIDExist($wxid,$aid="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT AID FROM `AGENT` WHERE WXID=? AND STATE=1 ";
        if(!empty($aid)){
            $sql.=" AND AID<>".$aid;
        }
        $query  = $DBData->query($sql, [$wxid]);
        return $query->num_rows() > 0;
    }
    /**
     * 验证游戏ID是否存在
     */
    function isGIDExist($uno,$aid="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT AID FROM `AGENT` WHERE UNO=? AND STATE=1 ";
        if(!empty($aid)){
            $sql.=" AND AID<>".$aid;
        }
        $query  = $DBData->query($sql, [$uno]);
        return $query->num_rows() > 0;
    }

    /**
     * 获取验证码
     */
    function getValidateCode($mobile) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "SELECT validnum FROM phone_valid WHERE cellphone=? AND ctime>? AND isvalid=1";
        $sqlParam = array(
            $mobile,
            date("Y-m-d H:i:s", time() - 5 * 60)
        );
        $query    = $DBData->query($sql, $sqlParam);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->validnum;
        }
        return "";
    } 

    /**
     * 根据登录 Token 获取代理商信息
     */
    function getAgentData($token) {
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
    function getSysRcNum() {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "
                SELECT ROOMCARD FROM
                    `AGENT`  
                WHERE  AID=? AND STATE=?";
        $query  = $DBData->query($sql, [SysDict::$SYSTEM_AGENT,  SysDict::$SYSTEM_AGENT_STATE]);
        if ($query->num_rows() > 0) {
            $res = $query->row_array();
            return (int)$res["ROOMCARD"];
        }
        return 0;
    }
    /**
     * 根据代理商ID获取代理商信息
     */
    function getAgentById($aid,$paid="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "
                SELECT * 
                FROM
                    `AGENT` 
                WHERE 
                    STATE=1 AND AID=?";
        $param = [$aid];
        if(!empty($paid)){
            $sql .= " AND PAID=?"; 
            array_push($param, $paid);
        }
        $query  = $DBData->query($sql, $param);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return "";
    }
    /**
     * 根据代理商ID获取代理商信息
     */
    function getAgentByUno($uno,$paid="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "
                SELECT * 
                FROM
                    `AGENT` 
                WHERE 
                    STATE=1 AND UNO=?";
        $param = [$uno];
        if(!empty($paid)){
            $sql .= " AND PAID=?"; 
            array_push($param, $paid);
        }
        $query  = $DBData->query($sql, $param);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return "";
    }
    /**
     * 获取代理商信息
     */
    function getAgentInfo($aid) {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "
                SELECT * 
                FROM
                    `AGENT` 
                WHERE 
                    STATE=1 AND AID=? ";
        $query = $DBData->query($sql, [$aid]);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return "";
    }
    /**
     * 查找下级代理商
     */
    function getLowerAgentListData($aid,$search,$level=""){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT WHERE STATE = 1";
        $arr = [];
        if(!empty($aid)){
            $sql .= " AND PAID = ?";
            array_push($arr, $aid);
        }
        if(!empty($level)){
            $sql .= " AND LEVEL = ?";
            array_push($arr, $level);
        }
        if(!empty($search)){
            $sql = $sql ." AND AID LIKE '%".$search."%'";
        } 
        $query  = $DBData->query($sql, $arr);
        if ($query->num_rows() > 0) { 
             return $query->result_array();
        }
        return "";
    }
    function getLowerAgentListDataCount($aid="", $search="",$level="") {
        $DBData = $this->load->database($this->dbName, TRUE);
//        $sql = "SELECT * FROM AGENT WHERE PAID = ? AND STATE = 1";
//        $arr = [$aid];
//        if(!empty($level) || $level){
//            $sql = "SELECT * FROM AGENT WHERE STATE = 1 AND LEVEL = ?";
//            $arr = [$level];
//        }
        $sql = "SELECT * FROM AGENT WHERE STATE = 1";
        $arr = [];
        if(!empty($aid)){
            $sql .= " AND PAID = ?";
            array_push($arr, $aid);
        }
        if(!empty($level)){
            $sql .= " AND LEVEL = ?";
            array_push($arr, $level);
        }
        if(!empty($search)){
            $sql = $sql ." AND AID LIKE '%".$search."%'";
        }
        $query  = $DBData->query($sql, $arr);
        $row    = $query->row();
        if (isset($row)) {
            return $query->num_rows();
        }
        return 0;
    }
    //获取分享内容说明
    function getShareContent($aid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_SHARE WHERE AID = ? ";
        $query  = $DBData->query($sql, [$aid]);
        if ($query->num_rows() > 0) { 
             return $query->result_array();
        }
        return "";
    }
    //新增分享内容说明
    function saveShareContent($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "INSERT INTO `AGENT_SHARE` (
                                `SID`,
                                `CONTENT`,
                                `AID`,
                                `CTIME`,
                                `MTIME` )
                       VALUES (?,?,?,?,?)";
        $sqlParam = array(
            StringUtil::uuid(),  
            $param["CONTENT"],
            $param["AID"], 
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()) 
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    //修改分享内容说明
    function updateShareContent($param){ 
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "UPDATE AGENT_SHARE
                    SET CONTENT =?, MTIME =?
                    WHERE
                            AID =?";
        $sqlParam = array( 
            $param["CONTENT"], 
            date("Y-m-d H:i:s", time()),
            $param["AID"] 
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    //修改有效期
    
}
