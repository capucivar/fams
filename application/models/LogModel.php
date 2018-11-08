<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 

class LogModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    } 
   
    function saveLog($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "INSERT INTO AGENT_LOG (LID,LTYPE,AID,CONTENT,IP,CTIME) VALUES (?,?,?,?,?,?)";
        $id = empty($param["LID"])?StringUtil::orderid():$param["LID"];
        $sqlParam = array(
            $id,
            $param["LTYPE"],
            $param["AID"], 
            $param["CONTENT"],
            StringUtil::getClientIP(),
            date("Y-m-d H:i:s", time()));
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function saveRcLog($param,$param2=""){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "INSERT INTO AGENT_ROOMCARD_LOG (ID,TYPE,NUM,OPERATOR,AID,TOAID,MONEY,ACTUALPAY,RCID,MARK,CTIME,AREA) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $sqlParam = array(
            empty($param["ID"])?StringUtil::orderid(23, SysDict::$EXUID["default"]):$param["ID"],
            $param["TYPE"],
            $param["NUM"],
            $param["OPERATOR"],
            $param["AID"], 
            $param["TOAID"],
            empty($param["MONEY"])?"":$param["MONEY"],
            empty($param["ACTUALPAY"])?"":$param["ACTUALPAY"],
            empty($param["RCID"])?"":$param["RCID"],
            empty($param["MARK"])?"":$param["MARK"], 
            date("Y-m-d H:i:s", time()),
            empty($param["AREA"])?"":$param["AREA"]);
        
        if(!empty($param2)){
            $sql.=",(?,?,?,?,?,?,?,?,?,?,?,?)";
            array_push($sqlParam, empty($param2["ID"])?StringUtil::orderid(23, SysDict::$EXUID["default"]):$param2["ID"]);
            array_push($sqlParam, $param2["TYPE"]);
            array_push($sqlParam, $param2["NUM"]);
            array_push($sqlParam, $param2["OPERATOR"]);
            array_push($sqlParam, $param2["AID"]);
            array_push($sqlParam, $param2["TOAID"]);
            array_push($sqlParam, empty($param2["MONEY"])?"":$param2["MONEY"]); 
            array_push($sqlParam, empty($param2["ACTUALPAY"])?"":$param2["ACTUALPAY"]); 
            array_push($sqlParam, empty($param2["RCID"])?"":$param2["RCID"]); 
            array_push($sqlParam, empty($param2["MARK"])?"":$param2["MARK"]); 
            array_push($sqlParam, date("Y-m-d H:i:s", time())); 
            array_push($sqlParam, empty($param2["AREA"])?"":$param2["AREA"]); 
        }
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function saveRcLog_bak($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "INSERT INTO AGENT_LOG_RCHARGE (RCID,LTYPE,RCOUNT,AID,RCTO,MONEY,ACTUALPAY,RCLEVEL,CTIME,AREA) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $id = empty($param["RCID"])?StringUtil::uuid():$param["RCID"];
        $area= empty($param["AREA"])?"":$param["AREA"];
        $sqlParam = array(
            $id,
            $param["LTYPE"],
            $param["RCOUNT"],
            $param["AID"], 
            $param["RCTO"],
            $param["MONEY"],
            $param["ACTUALPAY"],
            $param["RCLEVEL"], 
            date("Y-m-d H:i:s", time()),
            $area);
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    
    //根据操作类型获取日志
    function getLogList($aid,$search){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT * FROM AGENT_LOG WHERE AID = ? ";
        if(!empty($search)){
            $sql = $sql ." AND ( CONTENT LIKE '%".$search."%')";
        }
        $sql = $sql ." ORDER BY CTIME DESC";
        $query  = $DBData->query($sql, [$aid]);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return "";
    }
    function getLogListCount($aid,$search){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT * FROM AGENT_LOG WHERE AID = ? ";
        if(!empty($search)){
            $sql = $sql ." AND ( CONTENT LIKE '%".$search."%')";
        }
        $sql = $sql ." ORDER BY CTIME DESC";
        $query  = $DBData->query($sql, [$aid]);
        $row    = $query->row();
        if (isset($row)) {
            return $query->num_rows();
        }
        return 0;
    } 
    //获取房卡充值/转让记录
    
    function getRcLogList($aid,$type){
        $DBData = $this->load->database($this->dbName, TRUE); 
        $sql    = "SELECT A.*,B.UNO,B.WXID FROM AGENT_ROOMCARD_LOG AS A LEFT JOIN AGENT_PLAYER B ON A.TOAID=B.PID WHERE 1=1 ";
        $sqlParam = [];
        if(!empty($aid)){ 
            $sql .= " AND A.AID = ?";
            array_push($sqlParam, $aid);
        }
        if(!empty($type)){ 
            $sql.= " AND A.TYPE = ?";
            array_push($sqlParam, $type);
        }
        $sql.= " ORDER BY A.CTIME DESC";
        $query  = $DBData->query($sql, $sqlParam);
        return $query->result_array();
    }
    
    function getRcLogList_bak($aid,$type,$search){
        $DBData = $this->load->database($this->dbName, TRUE);
//        $sql    = "SELECT * FROM AGENT_LOG_RCHARGE WHERE (AID = ? OR RCTO = ?)";
        $sql    = "SELECT concat(A.LTYPE,'-',A.RCTO) AS LTYPE2,B.GID,B.UNO,B.NICKNAME,A.* FROM AGENT_LOG_RCHARGE AS A LEFT JOIN AGENT_PLAYER AS B ON A.RCTO = B.PID WHERE (A.AID = ? OR A.RCTO = ?)";
        if(!empty($type)){
            if($type=="99")
                $sql.= " AND A.LTYPE = 21 AND A.RCTO = ".$aid;
            else if($type=="21")
                $sql.= " AND A.LTYPE = 21 AND A.RCTO <> ".$aid;
            else
                $sql.= " AND A.LTYPE = ".$type;
        }
        if(!empty($search)){ 
            $sql .= " AND A.RCID = ".$search;
        }
        $sql.= " ORDER BY A.CTIME DESC";
        $query  = $DBData->query($sql, [$aid,$aid]);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return "";
    }
    function getRcLogListCount($aid,$type,$search){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql    = "SELECT concat(A.LTYPE,'-',A.RCTO) AS LTYPE2,B.GID,A.* FROM AGENT_LOG_RCHARGE AS A LEFT JOIN AGENT_PLAYER AS B ON A.RCTO = B.PID WHERE (A.AID = ? OR A.RCTO = ?)";
        if(!empty($type)){
            if($type=="99")
                $sql.= " AND A.LTYPE = 21 AND A.RCTO = ".$aid;
            else if($type=="21")
                $sql.= " AND A.LTYPE = 21 AND A.RCTO <> ".$aid;
            else
                $sql.= " AND A.LTYPE = ".$type;
        }
        if(!empty($search)){ 
            $sql .= " AND A.RCID = ".$search;
        }
        $sql.= " ORDER BY A.CTIME DESC";
        
        $query  = $DBData->query($sql, [$aid,$aid]);
        $row    = $query->row();
        if (isset($row)) {
            return $query->num_rows();
        }
        return 0;
    } 
}
