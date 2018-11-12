<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php"); 

class ReceiveC extends BaseC { 
    public function __construct() {
        parent::__construct();
        $this->load->model("ReceiveM");
        $this->load->model("AssetModel");
    }
    public function index() {
        $data["baseInfo"]        = $this->baseInfo;
        $data["menuCatagery"] = "物品领用";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data); 
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('asset/receive');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function newform() {
        $param = $_REQUEST;
        $form = array("formid"=>"","assetid"=>"","userid"=>"","num"=>"","note"=>"");
        $data["baseInfo"]        = $this->baseInfo;
        $data["form"]  = $form;
        $data["menuCatagery"] = "资产管理";
        $data["menuSub"]      = "新增物品领用单";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data); 
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('asset/receivenew');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function getList(){ 
        $rows   = $this->ReceiveM->getList();
        $count  = count($rows);
        parent::echoLocalBootstrapTableData($rows, $count);
    }
    public function save(){
        $param        = $_REQUEST;
        $params_keys = ["assetid","userid","num"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        //检测库存数量
        $storenum = $this->AssetModel->getStorenumById($param["assetid"]);
        if($storenum-$param["num"]<0)
            parent::echoFail("当前库存数量：".$storenum."，少于领用数量");
        $param["formid"] = StringUtil::randStr(10,'NUMBER');
        $res = $this->ReceiveM->save($param);
        if($res){
            parent::echoSuccess("操作成功");
        }
        parent::echoFail("操作失败");
    }
    
    public function delete(){
        $param        = $_REQUEST;
        $params_keys = ["formid"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");  
        $result = $this->ReceiveM->delete($param);
        if ($result) {
            parent::echoSuccess("操作成功");
        } else {
            parent::echoFail("操作失败");
        }
    }
}
