<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "SysDict.php");

class NoticeC extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("NoticeModel");
    }
    
    public function index() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "公告";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 
        $data["lowerAgent"]       = "";
 
        $this->load->view('common/header', $data);
//        $this->load->view('common/menu');
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('notice');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //发布/修改公告
    public function notice() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "公告";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 
        $data["notice"]       = "";
        $nid = parent::getParam("nid");
        if (isset($nid) && !empty($nid)) {
            $data["notice"] = json_encode($this->NoticeModel->getNoticeById($nid,  $this->agent["AID"]));
        }
        $this->load->view('common/header', $data);
//        $this->load->view('common/menu');
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('noticenew');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function noticeList(){
        $agent_level = $this->agent["LEVEL"];
        $level = 1;
        $search = parent::getParam("search");  
        $paid    = $this->agent["PAID"];
        $rows   = $this->NoticeModel->getNoticeList($level,$paid, $search);
        $count  = $this->NoticeModel->getNoticeListCount($level,$paid, $search);
        parent::echoBootstrapTableData($rows, $count);
    }
    public function saveNotice(){
        $param        = $_REQUEST; 
        if(empty($param)){
            parent::echoFail("保存数据失败");
        }
        $nid = $param["NID"];
        $content = $param["CONTENT"];
        // 保存数据
        if (!empty($nid)) {
            $result = $this->NoticeModel->updateNotice($nid,$content);
        }else{
            $param["AID"]= $this->agent["AID"];
            $result = $this->NoticeModel->addNotice($param);
        }
        if ($result) {
            parent::echoSuccess("保存成功");
        } else {
            parent::echoFail("保存数据失败");
        }
    }
    public function delNotice(){
        $nid = parent::getParam("nid"); 
        $result = $this->NoticeModel->delNotice($nid);
        if ($result) {
            parent::echoSuccess("删除成功");
        } else {
            parent::echoFail("删除数据失败");
        }
    }
}
