<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php");  
class MessageModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    } 
    function getNoticeById($alid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_APPLY_LOG WHERE ALID = ? ";
        $sqlParam = array($nid);
        $query  = $DBData->query($sql,$sqlParam);
        if ($query->num_rows() > 0) { 
             return $query->row_array();
        }
        return "";
    }
    function getMsgList($start,$num){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_APPLY_LOG WHERE STATE = 1  ORDER BY CTIME DESC LIMIT ".$start.",".$num;
        $query  = $DBData->query($sql);
        if ($query->num_rows() > 0) { 
             return $query->result_array();
        }
        return "";
    }
    function getMsgListCount(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_APPLY_LOG WHERE STATE = 1  ORDER BY CTIME DESC";
        $query  = $DBData->query($sql);
        $row    = $query->row();
        if (isset($row)) {
            return $query->num_rows();
        }
        return 0;
    } 
    //提交申请
    function saveApplyLog($param) {
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "INSERT INTO `AGENT_APPLY_LOG` (
                                `ALID`, 
                                `ALTYPE`,
                                `ALAID`, 
                                `ALPID`,
                                `LEVEL`, 
                                `CTIME`,
                                `MTIME` )
                       VALUES (?,?,?,?,?,?,?)";
        $sqlParam = array(
            StringUtil::uuid(), 
            $param["ALTYPE"],
            $param["ALAID"],
            $param["ALPID"], 
            $param["LEVEL"], 
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()) 
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
}
