<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 
include_once(APP_PATH_L . "SysDict.php"); 

class NoticeModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    } 
    //获取最新一条公告
    function getNoticeNew(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_NOTICE WHERE STATE = 1 ORDER BY CTIME DESC LIMIT 1 ";
        $query  = $DBData->query($sql);
        if ($query->num_rows() > 0) { 
             return $query->row_array();
        }
        return "";
    }
    function getNoticeById($nid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_NOTICE WHERE STATE = 1 AND NID = ? ";
        $sqlParam = array($nid);
        $query  = $DBData->query($sql,$sqlParam);
        if ($query->num_rows() > 0) { 
             return $query->row_array();
        }
        return "";
    }
    function getNoticeList($level,$paid,$search){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_NOTICE WHERE STATE = 1 ";
        if($level==SysDict::$NOTICELEVEL["lower"]){//
            $sql .= " AND (NLEVEL IN (1,2) OR AID = ".$paid." )";
        }else{
            $sql .=" AND NLEVEL = 1";
        } 
        if(!empty($search)){
            $sql = $sql ." AND CONTENT LIKE '%".$search."%'";
        } 
        $sql.=" ORDER BY CTIME DESC";
        $query  = $DBData->query($sql);
        if ($query->num_rows() > 0) { 
             return $query->result_array();
        }
        return "";
    }
    function getNoticeListCount($level,$paid,$search){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM AGENT_NOTICE WHERE STATE = 1 ";
        if($level==SysDict::$NOTICELEVEL["lower"]){//
            $sql .= " AND (NLEVEL IN (1,2) OR AID = ".$paid." )";
        }else{
            $sql .=" AND NLEVEL = 1";
        } 
        if(!empty($search)){
            $sql = $sql ." AND CONTENT LIKE '%".$search."%'";
        }
        $query  = $DBData->query($sql);
        $row    = $query->row();
        if (isset($row)) {
            return $query->num_rows();
        }
        return 0;
    }
    //发布公告
    function addNotice($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "INSERT INTO `AGENT_NOTICE` (
                                `NID`,
                                `CONTENT`,
                                `AID`,
                                `CTIME`,
                                `NLEVEL` )
                       VALUES (?,?,?,?,?)";
        $sqlParam = array(
            StringUtil::uuid(),
            $param["CONTENT"],
            $param["AID"], 
            date("Y-m-d H:i:s", time()),
            $param["NLEVEL"] );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    //删除公告
    function delNotice($nid){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = " UPDATE 
                        `AGENT_NOTICE` 
                     SET `STATE`=0 ,`MTIME`=? 
                     WHERE `NID`=? ";
        $sqlParam = [
            date("Y-m-d H:i:s", time()),
            $nid ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    //修改公告内容
    function updateNotice($nid,$content){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = " UPDATE 
                        `AGENT_NOTICE` 
                     SET `CONTENT`=? ,`MTIME`=? 
                     WHERE `NID`=? ";
        $sqlParam = [
            $content,
            date("Y-m-d H:i:s", time()),
            $nid ];
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
}
