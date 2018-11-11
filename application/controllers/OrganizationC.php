<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "StringUtil.php");

class OrganizationC extends BaseC {
    public function __construct() {
        parent::__construct();
        $this->load->model("OrganizationM");
    }
    public function index() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "组织架构";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = "";
        $this->load->view('common/header', $data);
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('organization/organization');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function getOrganList(){
        $rows   = $this->OrganizationM->getOrganList();
        $data = json_encode($rows,JSON_NUMERIC_CHECK);
        echo $data;
    }
    public function newOrganInfo(){
        $param        = $_REQUEST;
        $pid = 100;
        //获取最大的一个ID
        $topid = 1;
        $topData = $this->OrganizationM->getIdBypid($pid);
        if (count($topData)>0){
            $topid = ((int)substr($topData[0]["deptid"],-3))+1;
        }
        $param["deptid"] = $param["parentid"].sprintf("%03d",$topid);
        $res = $this->OrganizationM->saveOrgan($param);
        if($res){
            parent::echoSuccess("保存成功");
        }
        parent::echoFail("保存失败");
    }
    public function delOrgan(){
        $param        = $_REQUEST;
        //检查改部门下是否有成员

        $result = $this->OrganizationM->delOrgan($param);
        if ($result) {
            parent::echoSuccess("删除成功");
        } else {
            parent::echoFail("删除失败");
        }
    }
    public function updateOrganInfo(){
        $param        = $_REQUEST;
        $result = $this->OrganizationM->updateOrganInfo($param);
        if ($result) {
            parent::echoSuccess("修改成功");
        } else {
            parent::echoFail("修改失败");
        }
    }
    public function getLevelOne(){
        $rows   = $this->OrganizationM->getLevelOne();
        $count  = count($rows);
        parent::echoLocalBootstrapTableData($rows,$count);
    }
    public function getLevelTwo(){
        $param = $_REQUEST;
        $params_keys = ["parentid"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        $rows   = $this->OrganizationM->getLevelTwo($param["parentid"]);
        $count  = count($rows);
        parent::echoLocalBootstrapTableData($rows,$count);
    }
}
