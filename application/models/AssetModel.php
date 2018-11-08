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
        $sql = "SELECT * FROM asset WHERE 1=1 and isvalid=1";
        $sqlParam = []; 
        $sql.="  ORDER BY ctime DESC";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array();
    }
    
//    --------
    function saveAsset($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql      = "INSERT INTO `asset` (
                                `assetid`,
                                `typeid`,
                                `placeid`,
                                `assetcode`,
                                `assetname`,
                                `brand`,
                                `size`,
                                `unitprice`,
                                `storenum`,
                                `note`,
                                `isvalid`, 
                                `ctime`,
                                `mtime`)
                       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $sqlParam = array(
            $param["assetid"],
            $param["typeid"],
            $param["placeid"],
            $param["assetcode"],
            $param["assetname"],
            $param["brand"],
            $param["size"],
            $param["unitprice"],
            $param["storenum"],
            $param["note"],
            1,
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()) 
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    } 
    
    
     //修改活动信息
    function updateAsset($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE asset SET 
         typeid = ?,
         placeid = ?,
         assetcode = ?,
         assetname = ?,
         brand = ?,
         size = ?,
         unitprice = ?,
         storenum = ?,
         note = ?,
         mtime = ?
         WHERE assetid=?";
        $sqlParam = array( 
            $param["typeid"],
             $param["placeid"],
             $param["assetcode"],
             $param["assetname"],
             $param["brand"],
             $param["size"],
             $param["unitprice"],
             $param["storenum"],
             $param["note"],
             date("Y-m-d H:i:s", time()),
             $param["assetid"] 
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
}
