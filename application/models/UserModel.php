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
        $sql      = "SELECT * FROM `user` WHERE phone=? AND PASSWORD=? AND state=1 AND isvalid=1 and isadmin=1";
        $sqlParam = array(
           $uid,
           md5($pwd));
        $query    = $DBData->query($sql, $sqlParam);
        return $query->num_rows() > 0;
    }
    
    function getUserList(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT a.*,b.deptname
FROM `user` as a
INNER JOIN department as b ON a.deptid=b.deptid
WHERE state = 1 AND a.isvalid = 1
ORDER BY a.CTIME DESC";
        $param = [];
        $query  = $DBData->query($sql, $param);
        return $query->result_array();
    }

    function getUserInfoById($userid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM `user` WHERE state = 1 AND isvalid=1 AND userid=? ";
        $param = [$userid];
        $query  = $DBData->query($sql, $param);
        return $query->result_array();
    }
    function getUserInfoTop(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM `user` ORDER BY usercode DESC limit 1";
        $param = [];
        $query  = $DBData->query($sql, $param);
        return $query->result_array();
    }
    function saveUserInfo($param) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "INSERT INTO `user` (
                                `userid`, 
                                `deptid`,
                                `deptid2`,
                                `usercode`,
                                `password`, 
                                `username`,
                                `gender`,
                                `phone`,
                                `email`,
                                `isadmin`,
                                `isvalid`,
                                `ctime`,
                                `mtime` )
                       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $sqlParam = array(
            StringUtil::randStr(10,"NUMBER"),
            $param["deptid"],
            $param["deptid2"],
            $param["usercode"],
            $param["password"],
            $param["username"],
            $param["gender"],
            $param["phone"],
            $param["email"],
            $param["isadmin"],
            1,
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()) 
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    //$mobile2:不检查这个手机号
    function isMobileExist($mobile,$mobile2="") {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT * FROM `user` WHERE phone=? AND state=1 AND isvalid = 1 ";
        if (!empty($mobile))
            $sql.=" AND phone<>'".$mobile2."'";
        $query  = $DBData->query($sql, [$mobile]);
        return $query->num_rows() > 0;
    }

    function updateUserInfo($param) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
            UPDATE 
                `user` 
            SET 
                `deptid` = ?,
                `deptid2` = ?,
                `username` = ?,
                `gender`=?,
                `phone`=?,
                `email` = ?,
                `isadmin` = ?,
                `mtime`=?
            WHERE `userid`=?";
        $sqlParam = [
            $param["deptid"],
            $param["deptid2"],
            $param["username"],
            $param["gender"],
            $param["phone"],
            $param["email"],
            (int)$param["isadmin"],
            date("Y-m-d H:i:s", time()),
            $param["userid"],
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    
    function delUser($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "
                     UPDATE 
                        `user` 
                     SET `STATE`=0 ,`MTIME`=? 
                     WHERE `userid`=? ";
        $sqlParam = [
            date("Y-m-d H:i:s", time()),
            $param["userid"]
        ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
}
