<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php"); 

class AssetC extends BaseC { 
    public function __construct() {
        parent::__construct();
        $this->load->model("AssetModel");
        $this->load->model("AssetTypeM");
    }
    public function index() {
        $data["baseInfo"]        = $this->baseInfo;
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
        $param = $_REQUEST;
        $asset = array("assetid"=>"","assetcode"=>"","assetname"=>"","typename"=>"","typeid"=>"","typeid2"=>"","brand"=>"","size"=>"","storenum"=>"","isdisposable"=>"0","unitprice"=>"","note"=>"");
        if (isset($param["id"])){
            $assetid = $param["id"];
            $assetrows = $this->AssetModel->getAssetListById($assetid);
            $asset = count($assetrows)>0?$assetrows[0]:$asset;
        }
        $data["baseInfo"]        = $this->baseInfo;
        $data["asset"]  = $asset;
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
        parent::echoLocalBootstrapTableData($rows, $count);
    }
    public function getParentType(){
        $rows   = $this->AssetTypeM->getParentTypeList();
        $count  = count($rows);
        parent::echoBootstrapTableData($rows, $count);
    }
    public function getChildType(){
        $param = $_REQUEST;
        $rows   = $this->AssetTypeM->getChildTypeList($param["parentid"]);
        $count  = count($rows);
        parent::echoBootstrapTableData($rows, $count);
    }
    public function getAssetCode(){
        $param = $_REQUEST;
        $params_keys = ["typeid"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        $typerows   = $this->AssetTypeM->getTypeCodeById($param["typeid"]);
        $typecode="";
        if(count($typerows)>0)
            $typecode = $typerows[0]["typecode"];
        //获取当前类别下资产类别的编码
        $assetrows = $this->AssetModel->getAssetCodeByType($param["typeid"]);
        $assetcode = 1;
        if (count($assetrows)>0)
            $assetcode = ((int)substr($assetrows[0]["assetcode"],-3))+1;
        echo $typecode.sprintf("%03d",$assetcode);;
    }
    public function saveAsset(){
        $param        = $_REQUEST;
        $params_keys = ["typeid","assetname","assetcode","brand","size","unitprice","storenum","isdisposable"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        $res = false;
        if (empty($param["assetid"])){
            $param["assetid"] = StringUtil::randStr(10,'NUMBER');
            $res = $this->AssetModel->saveAsset($param);
        }else{
            $res = $this->AssetModel->updateAsset($param);
        }
        if($res){
            parent::echoSuccess("操作成功");
        }
        parent::echoFail("操作失败");
    }
}
