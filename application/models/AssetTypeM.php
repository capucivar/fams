<?php

include_once(APP_PATH_L . "DBUtil.php");
include_once(APP_PATH_L . "StringUtil.php");
class AssetTypeM extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    }

    function getTypeList(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM assetype WHERE 1=1 and isvalid=1  ORDER BY ctime DESC";
        $sqlParam = [];
        $query  = $DBData->query($sql);
        return $query->result_array();
    }
    function getParentTypeList(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM assetype WHERE 1=1 and isvalid=1 AND parentid=0 ORDER BY ctime DESC";
        $query  = $DBData->query($sql);
        return $query->result_array();
    }
    function getChildTypeList($parentid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM assetype WHERE 1=1 and isvalid=1 AND parentid=? ORDER BY ctime DESC";
        $sqlParam = [$parentid];
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }
    //根据父类ID获取子级节点
    function getTypeListByPid($pid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM assetype WHERE 1=1 and isvalid=1 AND parentid=? ORDER BY ctime DESC";
        $sqlParam = [$pid];
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }

    function getTypeCodeById($typeid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM assetype WHERE 1=1 and isvalid=1 AND typeid=? ORDER BY ctime DESC";
        $sqlParam = [$typeid];
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }

    function saveType($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "INSERT INTO `assetype` (
                                `typeid`,
                                `typename`,
                                `typecode`,
                                `parentid`, 
                                `isvalid`,
                                `ctime`,
                                `mtime`)
                       VALUES (?,?,?,?,?,?,?)";
        $sqlParam = array(
            $param["typeid"],
            $param["typename"],
            $param["typecode"],
            $param["parentid"],
            1,
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time())
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }

    function updateType($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE assetype SET 
         typename = ?,
         typecode = ?, 
         mtime = ?
         WHERE typeid=?";
        $sqlParam = array(
            $param["typename"],
            $param["typecode"],
            date("Y-m-d H:i:s", time()),
            $param["typeid"]
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }

    function delType($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE assetype SET 
         isvalid = ?, 
         mtime = ?
         WHERE typeid=?";
        $sqlParam = array(
            0,
            date("Y-m-d H:i:s", time()),
            $param["typeid"]
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
}
