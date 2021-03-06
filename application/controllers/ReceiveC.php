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
        $sdate = empty($_REQUEST["s"])? null : $_REQUEST["s"]." 00:00:00";
        $edate = empty($_REQUEST["e"])? null : $_REQUEST["e"]." 23:59:59";
        $rows   = $this->ReceiveM->getList($sdate,$edate);
        $count  = count($rows);
        parent::echoLocalBootstrapTableData($rows, $count);
    }
    public function save(){
        $param        = $_REQUEST;
        $params_keys = ["assetid","userid","num"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        //检测库存数量
        $asset = $this->AssetModel->getStorenumById($param["assetid"]);
        $storenum = $asset["storenum"];
        if($storenum-$param["num"]<0)
            parent::echoFail("当前库存数量：".$storenum."，少于领用数量");
        //获取资产是否为易耗品
        $param["state"] = $asset["isdisposable"]=="0"?1:0; 
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
    public function back(){
        $param        = $_REQUEST;
        $params_keys = ["formid","assetid","num"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");  
        $result = $this->ReceiveM->back($param);
        if ($result) {
            parent::echoSuccess("操作成功");
        } else {
            parent::echoFail("操作失败");
        }
    }
}
