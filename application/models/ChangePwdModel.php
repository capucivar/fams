<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 

class ChangePwdModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    } 
   /**
    * 获取/添加一条手机验证码记录
    */
    function getVcode($cellphone){
        $DBData   = $this->load->database($this->dbName, TRUE);
        //查询
        $sqlQuery = "SELECT VALIDNUM,CTIME 
FROM AGENT_PHONE_VALID  WHERE CELLPHONE = ? 
AND DATE_FORMAT(CTIME,'%Y-%m-%d %H:%i:%S') > ?
AND STATE = 1
ORDER BY CTIME DESC ";
        $sqlQueryParam = array($cellphone,date("Y-m-d H:i:s", time() - 5 * 60));
        $resultQuery    = $DBData->query($sqlQuery, $sqlQueryParam);
        if($resultQuery->num_rows() > 0){
            $resultArr = $resultQuery->result_array();
            $num = $resultArr[0]["VALIDNUM"];
            
            $sqlUpdate = "UPDATE AGENT_PHONE_VALID SET STATE = 0 ,MTIME = ? WHERE CELLPHONE = ? AND VALIDNUM<>? AND STATE = 1";
            $sqlUpdateParam = array(date("Y-m-d H:i:s",  time()),$cellphone,$num);
            $DBData->query($sqlUpdate, $sqlUpdateParam);
            
            return $num;
        }
        //没有查询到，添加一条
        $sql = "INSERT INTO AGENT_PHONE_VALID (ID,CELLPHONE,VALIDNUM,CTIME,MTIME) VALUES (?,?,?,?,?)";
        $valid = StringUtil::getRandomCode(6);
        $sqlParam = array(
            StringUtil::uuid(),
            $cellphone,
            $valid,
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()) );
        $DBData->query($sql, $sqlParam);
        if($DBData->affected_rows() == 1){
            return $valid;
        }
        return "";
    } 
    /**
     * 验证码是否有效
     */
    function checkVCode($cellphone,$validnum){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT ID FROM AGENT_PHONE_VALID WHERE CELLPHONE = ? AND VALIDNUM = ? AND STATE = 1";
        $sqlParam = array($cellphone,$validnum);
        $query    = $DBData->query($sql, $sqlParam);
        return $query->num_rows() > 0;
    }
    /**
     * 修改验证码状态
     */
    function updateState($cellphone){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE AGENT_PHONE_VALID SET STATE = 0 ,MTIME = ? WHERE CELLPHONE = ? AND STATE = 1";
        $sqlParam = array(date("Y-m-d H:i:s",  time()), $cellphone);
        $DBData->query($sql, $sqlParam); 
        return $DBData->affected_rows() > 0;
    }
    /**
     * 修改密码
     */
    function changePwd($aid,$pwd){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE AGENT SET PASSWORD = ? ,MTIME = ?,ISFIRST = 0 WHERE AID = ? AND STATE = 1";
        $sqlParam = array($pwd,date("Y-m-d H:i:s",  time()), $aid);
        $DBData->query($sql, $sqlParam); 
        return $DBData->affected_rows() > 0;
    }
}
