<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php"); 

class AssetC extends BaseC { 
    public function __construct() {
        parent::__construct();
        $this->load->model("AssetModel"); 
    }
    public function index() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "资产管理";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data); 
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('asset/index');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function newAsset() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "资产入库";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data); 
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('asset/assetnew');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function getAsset(){ 
        $rows   = $this->AssetModel->getAssetList();
        $count  = count($rows);
        parent::echoBootstrapTableData($rows, $count);
    }
    public function type() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "资产类别管理";
        $data["menuSub"]      = "资产类别";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data); 
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('asset/type');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function getTypeList(){
        
    }
    public function  test(){
        $arr = array(0,1,2,3,4,5);
        foreach ($arr as $val) {
            
        }
    }
}
