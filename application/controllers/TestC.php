<?php
/* 
 * 游戏记录管理
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "/PHPExcel/PHPExcel.php"); 

class TestC extends BaseC { 
    public function __construct() {
        parent::__construct();
        $this->load->model("TestModel");  
    }
    public function index(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "测试测试";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = "";  
        
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('test');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function get(){
        $rows   = $this->TestModel->get();
        $count = count($rows);  
        parent::echoLocalBootstrapTableData($rows, $count); 
    }
    public function update(){
        $rows   = $this->TestModel->get();
        $tmpArr = array();
        foreach ($rows as $row) { 
            if(in_array($row["OWNER"], $tmpArr))
                continue;
            $rcid = $row["RCID"];
            $owner = $row["OWNER"];
            $keyData = $this->TestModel->getAgentLog($rcid);
            if(count($keyData)!=1){
                $msg = "OWNER:".$row["OWNER"]."Agent_Log 查出来多条：：：".  count($keyData);
                $this->setLog($msg);
                continue;
            }
            $aid = $keyData[0]["AID"];
            $updateRes = $this->TestModel->updateAgentPlayer($aid,$owner);//set pid=$owner where aid=$aid
            if(!$updateRes)
                $msg = "【更新失败】OWNER:".$row["OWNER"]."；AID：".$aid;
            else{
                $msg = "【更新成功】OWNER:".$row["OWNER"]."；AID：".$aid;
                array_push($tmpArr, $owner);
            }
            $this->setLog($msg);
            $this->setLog("/******************结束**********************/");
        }
    }
    protected function setLog($data){
        $logfilename = APP_PATH_LOG.'/test_'.date("Ym").'.log';
        $fopen = fopen($logfilename,   'a+'); 
        fputs($fopen,   $data."\r\n"); 
        fclose($fopen); 
    }
}

