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
        $sqlQuery = "SELECT vcode,ctime 
FROM vcode  WHERE phone = ? 
AND DATE_FORMAT(ctime,'%Y-%m-%d %H:%i:%S') > ?
AND STATE = 1
ORDER BY CTIME DESC ";
        $sqlQueryParam = array($cellphone,date("Y-m-d H:i:s", time() - 5 * 60));
        $resultQuery    = $DBData->query($sqlQuery, $sqlQueryParam);
        if($resultQuery->num_rows() > 0){
            $resultArr = $resultQuery->result_array();
            $num = $resultArr[0]["vcode"]; 
            $sqlUpdate = "update vcode set state = 0 ,MTIME = ? WHERE phone = ? AND vcode<>? AND state = 1";
            $sqlUpdateParam = array(date("Y-m-d H:i:s",  time()),$cellphone,$num);
            $DBData->query($sqlUpdate, $sqlUpdateParam); 
            return $num;
        }
        //没有查询到，添加一条
        $sql = "INSERT INTO vcode (vcodeid,phone,vcode,state,isvalid,ctime,mtime) VALUES (?,?,?,?,?,?,?)";
        $valid = StringUtil::getRandomCode(6);
        $sqlParam = array(
            StringUtil::uuid(),
            $cellphone,
            $valid,
            1,
            1,
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
    function checkVCode($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM vcode WHERE phone = ? AND vcode = ? AND state = 1";
        $sqlParam = array($param["phone"],$param["vcode"]);
        $query    = $DBData->query($sql, $sqlParam);
        return $query->num_rows() > 0;
    } 
    
    /**
     * 修改密码
     */
    function changePwd($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql1 = "UPDATE user SET password = ? ,mtime = ? WHERE userid = ? AND isvalid = 1";
        $sqlParam1 = array( md5($param["newpwd"]),date("Y-m-d H:i:s",  time()), $param["userid"]);
        $sql2 = "UPDATE vcode SET STATE = 0 ,MTIME = ? WHERE phone = ? AND isvalid = 1";
        $sqlParam2 = array(date("Y-m-d H:i:s",  time()), $param["phone"]); 
        $sql3 = "DELETE FROM user_token WHERE userid=?";
        $sqlParam3 = array($param["userid"]); 
        $DBData->trans_start();
        $DBData->query($sql1, $sqlParam1);
        $DBData->query($sql2, $sqlParam2);
        $DBData->query($sql3, $sqlParam3);
        $DBData->trans_complete();
        return $DBData->trans_status();
    }
}
