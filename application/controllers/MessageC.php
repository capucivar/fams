<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "SysDict.php");

class MessageC extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("MessageModel");
    }
    
    public function index() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "消息中心";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 
        $data["lowerAgent"]       = "";
        $page = parent::getParam("p");
        $start = 0;
        $num = 3;
        if($page!=""){
            $start = ($page-1)*$num;
        }
        $count  = $this->MessageModel->getMsgListCount();
        $totalPage = (($count%$num)==0)?$count/$num:$count/$num+1;
        $data["total"] = $totalPage;
        $msg = $this->MessageModel->getMsgList($start,$num);
        $data["msg"] = $msg; 
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('message');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    } 
}
