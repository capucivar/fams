<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 
include_once(APP_PATH_L . "SysDict.php"); 

class TestModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    }
    //获取游戏统计数据
    function get(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT * FROM AGENT_ROOMCARD WHERE `owner` not IN (SELECT pid from AGENT_PLAYER) 
AND `owner` not IN (SELECT aid from AGENT) 
AND CTIME>'2017-11-30 23:59:59' 
AND STATE = 3 AND MARK='完成任务，赠送玩家' ORDER BY CTIME "; 
        $query  = $DBData->query($sql);
        return $query->result_array(); 
    }
    
    function getAgentLog($key){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT * FROM `AGENT_LOG` WHERE LTYPE=14 AND CONTENT like '%$key%' "; 
        $query  = $DBData->query($sql);
        return $query->result_array(); 
    }
    
    function updateAgentPlayer($gid,$pid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="UPDATE AGENT_PLAYER SET PID=? WHERE  GID=?"; 
        $DBData->query($sql, [$pid,$gid]);
        return $DBData->affected_rows() == 1;
    }
}
