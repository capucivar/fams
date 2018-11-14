<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 

class LoginModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称
    private $ghost_dbName = "ghost";
    
    function __construct() {
        parent::__construct();
    }
    /**
     * 验证账号密码是否正确
     */
    function isLoginValid($username, $pwd) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "SELECT * FROM `user` WHERE usercode=? AND password=? AND isvalid=1 and isadmin=1";
        $sqlParam = array(
           $username,
            md5($pwd));
        $query    = $DBData->query($sql, $sqlParam);
        return $query->num_rows() > 0;
    }
     /**
     * 根据Userid获取usercode
     */
    function getUserByCode($usercode) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "SELECT * FROM `user` WHERE usercode=? AND isvalid=1 ";
        $sqlParam = array($usercode); 
        $query    = $DBData->query($sql, $sqlParam);
        return $query->row_array();
    }
    /**
     * 获取登录 token
     */
    function getToken($uid) {
        $db        = $this->load->database($this->dbName, TRUE);
        $sqlDelete = "DELETE FROM user_token WHERE userid=?";
        $sqlInsert = "INSERT INTO user_token (tokenid, token, userid, ctime, mtime ) VALUES (?,?,?,SYSDATE(),SYSDATE())";
        $db->trans_start();
        // 删除旧数据
        $db->query($sqlDelete, [$uid]);
        // 插入新数据
        $id = StringUtil::uuid(); 
        $db->query($sqlInsert, [$id, $id, $uid]);
        $db->trans_complete();
        if ($db->trans_status()) {
            return $id;
        }
        return "";
    }
    /**
     * 根据登录 Token 获取登录用户信息
     */
    function getUserData($token) {
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "
                SELECT
                        `user`.*,ut.ctime as logintime,dt.deptname 
                FROM
                        `user`
                INNER JOIN user_token AS ut ON `user`.userid = ut.userid
                INNER JOIN department AS dt ON `user`.deptid = dt.deptid
                WHERE
                        ut.token = ?
                AND `user`.isvalid = 1
                AND ut.isvalid = 1";
        $query  = $DBData->query($sql, [$token]);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return "";
    }
}
