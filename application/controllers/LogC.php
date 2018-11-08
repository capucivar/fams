<?php
/**
 * 下级代理商
 * **/
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 

class LogC extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("LogModel");
    } 
    public function index() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "查看日志";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 
        $data["lowerAgent"]       = ""; 
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('log');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
   
    public function getLogList(){
        $search = parent::getParam("search"); 
        $aid    = $this->agent["AID"];
        $rows   = $this->LogModel->getLogList($aid, $search);
        $count  = $this->LogModel->getLogListCount($aid, $search);
        parent::echoBootstrapTableData($rows, $count);
    }
    public function getRcLogList_bak(){
        $search = parent::getParam("search"); 
        $type = parent::getParam("t");
        $aid    = $this->agent["AID"];
        $rows   = $this->LogModel->getRcLogList($aid,$type,$search); 
        $count  = $this->LogModel->getRcLogListCount($aid,$type,$search); 
        parent::echoBootstrapTableData($rows, $count);
    }
    
    public function getRcLogList(){
        $type = parent::getParam("t");
        $aid    = $this->agent["AID"];
        $rows   = $this->LogModel->getRcLogList($aid,$type); 
        $count  = count($rows);
        parent::echoLocalBootstrapTableData($rows, $count);
    }
    
}
