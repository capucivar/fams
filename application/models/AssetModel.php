<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php");  
class AssetModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct(); 
    } 
     
    function getAssetList(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT A.assetid,A.assetcode,A.assetname,B.typename,A.brand,A.size,A.storenum,A.isdisposable,A.unitprice,A.note,A.ctime FROM asset as A
INNER JOIN assetype as B ON A.typeid=B.typeid
WHERE 1=1 and A.isvalid=1
ORDER BY A.typeid,A.ctime DESC";
        $sqlParam = [];
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }
    function getAssetListById($assetid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT A.assetid,A.assetcode,A.assetname,A.typeid,A.typeid2,B.typename,A.brand,A.size,A.storenum,A.unitprice,A.isdisposable,A.note,A.ctime FROM asset as A
INNER JOIN assetype as B ON A.typeid=B.typeid
WHERE 1=1 and A.isvalid=1 AND A.assetid=?
ORDER BY A.typeid,A.ctime DESC";
        $sqlParam = [$assetid];
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }
    function getStorenumById($assetid){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM asset WHERE 1=1 and isvalid=1 AND assetid=?";
        $sqlParam = [$assetid];
        $query  = $DBData->query($sql,$sqlParam);
        $row = $query->row_array();
        return $row["storenum"]; 
    }
    //根据资产类别获取资产，按照code排序最大的
    function getAssetCodeByType($typeid,$checkIsValid=false){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT * FROM asset WHERE 1=1 and typeid=? ";
        if ($checkIsValid)
            $sql.=" AND isvalid=1";
        $sql.=" ORDER BY assetcode DESC LIMIT 1";
        $sqlParam = [$typeid];
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }
    function saveAsset($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "INSERT INTO `asset` (
                                `assetid`,
                                `typeid`,
                                `typeid2`,
                                `placeid`,
                                `assetcode`,
                                `assetname`,
                                `brand`,
                                `size`,
                                `unitprice`,
                                `storenum`,
                                `isdisposable`,
                                `note`,
                                `isvalid`, 
                                `ctime`,
                                `mtime`)
                       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $sqlParam = array(
            $param["assetid"],
            $param["typeid"],
            $param["typeid2"],
            1,
            $param["assetcode"],
            $param["assetname"],
            $param["brand"],
            $param["size"],
            $param["unitprice"],
            $param["storenum"],
            $param["isdisposable"],
            $param["note"],
            1,
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()) 
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    } 

    function updateAsset($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE asset SET 
         typeid = ?,
         typeid2 = ?, 
         assetcode = ?,
         assetname = ?,
         brand = ?,
         `size` = ?,
         unitprice = ?,
         storenum = ?,
         isdisposable = ?,
         note = ?,
         mtime = ?
         WHERE assetid=?";
        $sqlParam = array( 
            $param["typeid"],
             $param["typeid2"],
             $param["assetcode"],
             $param["assetname"],
             $param["brand"],
             $param["size"],
             $param["unitprice"],
             $param["storenum"],
             $param["isdisposable"],
             $param["note"],
             date("Y-m-d H:i:s", time()),
             $param["assetid"] 
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
}
