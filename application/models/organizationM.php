<?php

include_once(APP_PATH_L . "DBUtil.php");
include_once(APP_PATH_L . "StringUtil.php");
class OrganizationM extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    }

    function getOrganList(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT deptid,deptname,parentid FROM department WHERE 1=1 and isvalid=1 ";
        $sqlParam = [];
        $sql.="  ORDER BY deptid";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }
    function getLevelOne(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM department WHERE parentid=100 and isvalid=1";
        $sqlParam = [];
        $sql.="  ORDER BY deptid";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }
    function getLevelTwo($parentid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM department WHERE parentid=? and isvalid=1";
        $sql.="  ORDER BY deptid";
        $sqlParam = [$parentid];
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }
    //根据pid获取子节点ID最大的
    function getIdBypid($pid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT deptid FROM department WHERE parentid=? ORDER BY deptid DESC LIMIT 1";
        $sqlParam = [$pid];
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }

    function saveOrgan($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "INSERT INTO `department` (
                                `deptid`,
                                `deptname`,
                                `parentid`,
                                `isvalid`,
                                `ctime`,
                                `mtime`)
                       VALUES (?,?,?,?,?,?)";
        $sqlParam = array(
            $param["deptid"],
            $param["deptname"],
            $param["parentid"],
            1,
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time())
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }


    //修改信息
    function updateOrganInfo($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE department SET 
         deptname = ?,
         mtime = ?
         WHERE deptid=?";
        $sqlParam = array(
            $param["deptname"],
            date("Y-m-d H:i:s", time()),
            $param["deptid"]
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    //删除信息
    function delOrgan($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE department SET 
         isvalid = ?,
         mtime = ?
         WHERE deptid=?";
        $sqlParam = array(
            0,
            date("Y-m-d H:i:s", time()),
            $param["deptid"]
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
}
