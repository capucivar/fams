<?php
/**
物品领用单
 *  */
include_once(APP_PATH_L . "DBUtil.php");
include_once(APP_PATH_L . "StringUtil.php");
class ReceiveM extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    }

    function getList(){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql = "SELECT
	a.formid,
        a.assetid,
	b.assetcode,
	b.assetname,
	b.brand,
	b.size,
        b.isdisposable,
	c.username,
	a.num,
	a.ctime,
	a.note,
        a.state as status
FROM
	receive_form AS a
INNER JOIN asset AS b ON a.assetid = b.assetid
INNER JOIN `user` AS c ON a.userid = c.userid
WHERE
	a.isvalid = 1
ORDER BY
	ctime DESC";
        $sqlParam = [];
        $query  = $DBData->query($sql);
        return $query->result_array();
    } 
    function save($param){ 
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql1      = "INSERT INTO `receive_form` (
                                `formid`,
                                `assetid`,
                                `userid`,
                                `num`, 
                                `note`,
                                `state`,
                                `isvalid`,
                                `ctime`,
                                `mtime`)
                       VALUES (?,?,?,?,?,?,?,?,?)";
        $sqlParam1 = array(
            $param["formid"],
            $param["assetid"],
            $param["userid"],
            $param["num"],
            $param["note"], 
            $param["state"], 
            1,
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time())
        );
        $sql2 = "update asset SET storenum=storenum-? WHERE assetid=?";
        $sqlParam2 = array(
            $param["num"],
            $param["assetid"]);
        $DBData->trans_start();
        $DBData->query($sql1, $sqlParam1);
        $DBData->query($sql2, $sqlParam2);
        $DBData->trans_complete();
        return $DBData->trans_status();
    }
    function delete($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "UPDATE receive_form SET 
         isvalid = ?, 
         mtime = ?
         WHERE formid=?";
        $sqlParam = array(
            0,
            date("Y-m-d H:i:s", time()),
            $param["formid"]
        );
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    function back($param){ 
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql1      = "UPDATE receive_form SET 
         state = ?, 
         mtime = ?
         WHERE formid=?";
        $sqlParam1 = array(
            2,
            date("Y-m-d H:i:s", time()),
            $param["formid"]
        );
        $sql2 = "update asset SET storenum=storenum+? WHERE assetid=?";
        $sqlParam2 = array(
            $param["num"],
            $param["assetid"]);
        $DBData->trans_start();
        $DBData->query($sql1, $sqlParam1);
        $DBData->query($sql2, $sqlParam2);
        $DBData->trans_complete();
        return $DBData->trans_status();
    }
}
