<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php");

class AssetTypeC extends BaseC {
    public function __construct() {
        parent::__construct();
        $this->load->model("AssetTypeM");
        $this->load->model("AssetModel");
    }
    public function index() {
        $data["baseInfo"]        = $this->baseInfo;
        $data["menuCatagery"] = "资产类别管理";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data);
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('asset/type');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }

    public function getAssetType(){
        $rows   = $this->AssetTypeM->getTypeList();
        $data = json_encode($rows,JSON_NUMERIC_CHECK);
        echo $data;
    }

    public function newType(){
        $param        = $_REQUEST;
        $param["typeid"] = StringUtil::randStr(10,'NUMBER');
        $params_keys = ["typeid","typename","typecode","parentid"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        $res = $this->AssetTypeM->saveType($param);
        if($res){
            parent::echoSuccess("保存成功");
        }
        parent::echoFail("保存失败");
    }
    public function updateType(){
        $param        = $_REQUEST;
        $params_keys = ["typeid","typename","typecode"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        $result = $this->AssetTypeM->updateType($param);
        if ($result) {
            parent::echoSuccess("修改成功");
        } else {
            parent::echoFail("修改失败");
        }
    }
    public function delType(){
        $param        = $_REQUEST;
        $params_keys = ["typeid"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        //检查是否有下级
        $typeid = $param["typeid"];
        $child = $this->AssetTypeM->getTypeListByPid($typeid);
        if (count($child)>0)
            parent::echoFail("该类别下还有子类别，无法删除");
        //检查是否有资产
        $assetRows = $this->AssetModel->getAssetCodeByType($typeid,true);
        if (count($assetRows)>0){
            parent::echoFail("该类别下还有资产，无法删除");
        }
        $result = $this->AssetTypeM->delType($param);
        if ($result) {
            parent::echoSuccess("删除成功");
        } else {
            parent::echoFail("删除失败");
        }
    }
}
