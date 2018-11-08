<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 
include_once(APP_PATH_L . "SysDict.php"); 

class IGameDataModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    }
    function  deldata($date,$rno,$uno){ 
        $DBData = $this->load->database($this->dbName, TRUE); 
        $sql = "DELETE FROM game_score_report WHERE date=? AND rno=? AND uno=?";
        $sqlParam = array(
            $date,
            $rno,
            $uno); 
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function addData($sqlParam){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "INSERT INTO game_score_report (id,date,tno,tname,rno,ano,uno,nickname,score,others,rcount,paytype,stime,etime,minute,jucount,roundcount,ctime,mtime) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function  delTData($date,$uno,$tno){ 
        $DBData = $this->load->database($this->dbName, TRUE); 
        $sql = "DELETE FROM game_teahouse_report WHERE date=? and uno=? and tno=?";
        $sqlParam = array(
            $date,$uno,$tno);
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function addTData($sqlParam){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "INSERT INTO game_teahouse_report VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function  delGhostData($date){ 
        $DBData = $this->load->database($this->dbName, TRUE); 
        $sql = "DELETE FROM ghost_report WHERE date=?";
        $sqlParam = array(
            $date);
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function addGhostData($sqlParam){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "INSERT INTO ghost_report VALUES(?,?,?,?,?,?,?,?,?,?)";
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function updatePlayerData($sqlParam){
        $DBData = $this->load->database($this->dbName, TRUE); 
        $sql = "UPDATE AGENT_PLAYER SET UNO=?,WXID=?,NICKNAME=?,LASTLOGIN=?,MTIME=? WHERE GID=?"; 
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function getPlayerData($gid){
        $DBData = $this->load->database($this->dbName, TRUE); 
        $sql = "SELECT * FROM AGENT_PLAYER WHERE GID=?";
        $sqlParam = array($gid);
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array(); 
    }
    function addPlayerData($sqlParam){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "INSERT INTO AGENT_PLAYER (PID,GID,UNO,WXID,NICKNAME,LASTLOGIN,PAID,STATE,CTIME) VALUES(?,?,?,?,?,?,?,?,?)";
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
}
