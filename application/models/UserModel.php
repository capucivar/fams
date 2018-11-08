<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class UserModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称
    private $ghost_dbName = "ghost";
    
    function __construct() {
        parent::__construct();
    }
    /**
     * 验证账号密码是否正确
     */
    function isLoginValid($uid, $pwd) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "SELECT * FROM `user` WHERE phone=? AND PASSWORD=? AND isvalid=1";
        $sqlParam = array(
           $uid,
           $pwd ); 
        $query    = $DBData->query($sql, $sqlParam);
        return $query->num_rows() > 0;
    }
    
    function getUserListData($pid,$search){
        $DBData = $this->load->database($this->dbName, TRUE);
//        $sql = "SELECT * FROM AGENT_PLAYER WHERE STATE = 1 AND PAID<>0 AND PAID<>'' AND PAID is not null";
        $sql = "SELECT * FROM AGENT_PLAYER WHERE STATE = 1 AND PAID=0 AND UNO IS NOT NULL AND UNO <>'' ORDER BY CTIME DESC";
        $param = [];
//        if(!empty($pid)){
//            $sql .=" AND PAID = ?";
//            array_push($param, $pid);
//        }
//        if(!empty($search)){
//            $sql = $sql ." AND PID LIKE '%".$search."%'";
//        } 
        $query  = $DBData->query($sql, $param);
        if ($query->num_rows() > 0) { 
             return $query->result_array();
        }
        return "";
    }
    function getUserListDataCount($pid, $search,$isall=0) {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_PLAYER WHERE STATE = 1 AND PAID<>0 AND PAID<>'' AND PAID is not null";
        $arr = [];
        if(!empty($pid)){
            $sql .=" AND PAID = ?";
            array_push($arr, $pid);
        }
        if(!empty($isall) && $isall==1){
            $sql = "SELECT * FROM AGENT_PLAYER WHERE STATE = 1";
            $arr = "";
        }
        if(!empty($search)){
            $sql = $sql ." AND PID LIKE '%".$search."%'";
        }
        $query  = $DBData->query($sql, $arr);
        $row    = $query->row();
        if (isset($row)) {
            return $query->num_rows();
        }
        return 0;
    }
    
    function getUserListById($pid="",$search=""){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_PLAYER WHERE STATE = 1";
        if(!empty($pid)){
            $sql .= " AND PAID IN (".$pid.")";
        }
        if(!empty($search)){
            $sql = $sql ." AND PID LIKE '%".$search."%'";
        } 
        $query  = $DBData->query($sql);
        if ($query->num_rows() > 0) { 
             return $query->result_array();
        }
        return "";
    }
    function getUserListByIdCount($pid, $search="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_PLAYER WHERE STATE = 1";
        if(!empty($pid)){
            $sql .= " AND PAID IN (".$pid.")";
        }
        if(!empty($search)){
            $sql = $sql ." AND PID LIKE '%".$search."%'";
        }
        $query  = $DBData->query($sql);
        $row    = $query->row();
        if (isset($row)) {
            return $query->num_rows();
        }
        return 0;
    }
    
    function getUserById($pid,$paid="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $param = [$pid];
        $sql    = " SELECT * FROM `AGENT_PLAYER` WHERE STATE=1 AND PID=?";
        if(!empty($paid)){
            $sql.="  AND PAID=?";
            array_push($param, $paid);
        }
        $query  = $DBData->query($sql, $param);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return "";
    }
    function getUserByGid($gid) {
        $DBData = $this->load->database($this->dbName, TRUE);
        $param = [$gid];
        $sql    = " SELECT * FROM `AGENT_PLAYER` WHERE STATE=1 AND GID=?"; 
        $query  = $DBData->query($sql, $param);
        if ($query->num_rows() > 0) {
            return $query->row_array();
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
        return $DBData->affected_rows() == 1;
    }
    
    function isMobileExist($mobile,$paid,$pid) {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT PID FROM `AGENT_PLAYER` WHERE CELLPHONE=? AND STATE=1 AND PAID = ?";
        if(!empty($pid)){
            $sql.=" AND PID<>".$pid;
        }
        $query  = $DBData->query($sql, [$mobile,$paid]);
        return $query->num_rows() > 0;
    }
    function isWXIDExist($wxid,$paid,$pid) {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT PID FROM `AGENT_PLAYER` WHERE WXID=? AND STATE=1 AND PAID = ?";
        if(!empty($pid)){
            $sql.=" AND PID<>".$pid;
        }
        $query  = $DBData->query($sql, [$wxid,$paid]);
        return $query->num_rows() > 0;
    }
    function isGIDExist($gid,$paid="",$pid="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT PID FROM `AGENT_PLAYER` WHERE GID=? AND STATE=1";
        if(!empty($paid)){
            $sql.=" AND PAID=".$paid;
        }
        if(!empty($pid)){
            $sql.=" AND PID<>".$pid;
        }
        $query  = $DBData->query($sql, [$gid]);
        return $query->num_rows() > 0;
    } 
    function updateUserInfo($param) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT_PLAYER` 
            SET 
                `GID` = ?,
                `UNO` = ?,
                `WXID`=?,
                `CELLPHONE`=?,
                `REMARK` = ?,
                `MTIME`=?
            WHERE `PID`=?";
        $sqlParam = [
            $param["GID"],
            $param["UNO"],
            $param["WXID"],
            $param["CELLPHONE"], 
            $param["REMARK"], 
            date("Y-m-d H:i:s", time()),
            $param["PID"],
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    
    function delUser($pid,$paid){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
                     UPDATE 
                        `AGENT_PLAYER` 
                     SET `STATE`=0 ,`MTIME`=? 
                     WHERE `PID`=? AND `PAID` = ?
                     ";
        $sqlParam = [
            date("Y-m-d H:i:s", time()),
            $pid,
            $paid
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    /**
     * 转让房卡
     */
    function transferRCard($num,$pid, $paid,$level) {
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
        $sql  = " UPDATE `AGENT_PLAYER` SET `ROOMCARD`=`ROOMCARD`+? ,`MTIME`=? WHERE `PID`=? AND `PAID` = ? and STATE=1 ";
        $sqlParam = [$num,date("Y-m-d H:i:s", time()),$pid, $paid ];
        $sql2 = " UPDATE `AGENT` SET `ROOMCARD`=`ROOMCARD`-? ,`MTIME`=? WHERE `AID`=?  and STATE=1 ";
        $sqlParam2 = [$num,date("Y-m-d H:i:s", time()),$paid ]; 
        $sql3 = "UPDATE AGENT_ROOMCARD SET `OWNER` = ?,LEVEL=?,`MTIME`=? WHERE RCID IN (".$ids.")";
        $sqlParam3 = [$pid,$level,date("Y-m-d H:i:s", time())];
        $DBData->trans_start(); 
        $DBData->query($sql, $sqlParam);
        $DBData->query($sql2, $sqlParam2);
        $DBData->query($sql3, $sqlParam3);
        $DBData->trans_complete(); 
        $res = $DBData->trans_status();
        return $res?$ids:$res; 
    }
    //获取游戏玩家列表
    public function getGhostUserlist(){
        $DBData = $this->load->database($this->ghost_dbName, TRUE);
        $sql  = " SELECT * FROM `user_info` WHERE auth_type=2 and isvalid = 1";
        $query  = $DBData->query($sql);
        if ($query->num_rows() > 0) {
           return $query->result_array();
        }
        return "";
    }
    /**
     * 修改有效期
     */
    public function updateValidate($date,$pid,$state=1) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT_PLAYER` 
            SET 
                `STATE`=?,
                `INVALIDATE`=?, 
                `MTIME`=?
            WHERE `PID`=?";
        $sqlParam = [
            $state,
            $date,
            date("Y-m-d H:i:s", time()),
            $pid,
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    /**
     * 解绑玩家
     */
    public function unbind($pid){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `AGENT_PLAYER` 
            SET 
                `PAID`= NULL, 
                `MTIME`=?
            WHERE `PID`=?";
        $sqlParam = [ 
            date("Y-m-d H:i:s", time()),
            $pid,
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    //获取玩家的上级代理商ID
    function getUpperId($gid=""){
        $DBData = $this->load->database($this->dbName, TRUE); 
        $sql = "SELECT A.PAID,A.GID FROM `AGENT_PLAYER` AS A LEFT JOIN AGENT AS B ON A.PAID=B.AID "
                . "WHERE A.STATE=1 AND B.`LEVEL`=3";
        $arr=[];
        if(!empty($gid)){
            $sql.=" AND A.GID=?";
            array_push($arr, $gid);
        }
        $query  = $DBData->query($sql, $arr);
        return $query->result_array();
    } 
}
