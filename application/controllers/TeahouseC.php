<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php"); 

class TeahouseC extends BaseC {

    public function __construct() {
        parent::__construct(); 
    }
    
    //茶馆详情
    public function tdetail($tid) {
        //获取茶馆详情
//        $tid="d59149c07d8911e7b948bbd05d8c9179"; 
        $data["result"]["avatar"] = "";
        $data["result"]["addr"] = "";
        $data["result"]["contact"] = ""; 
        $data["result"]["desc"] = "";
        $data["result"]["pics"] = array();
        $gameData = parent::HttpGet("getTeahouseInfo?tid=".$tid);
        $data = json_decode($gameData,true);  
        $data["result"]["tname"] = isset($data["result"]["tname"])?$data["result"]["tname"]:"";
        $data["result"]["avatar"] = isset($data["result"]["avatar"])?$data["result"]["avatar"]:"";
        $data["result"]["addr"] = isset($data["result"]["addr"])?$data["result"]["addr"]:"";
        $data["result"]["contact"] = isset($data["result"]["contact"])?$data["result"]["contact"]:"";
        $data["result"]["desc"] = isset($data["result"]["desc"])?$data["result"]["desc"]:""; 
        $data["result"]["pics"] = isset($data["result"]["pics"])?$data["result"]["pics"]:array();
//        print_r($data["result"]);
        $this->load->view('teahouse/o2odetail',$data["result"]);
    }
    
}