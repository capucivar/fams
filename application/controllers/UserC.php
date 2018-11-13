<?php
/* 
 * 玩家管理，二级代理商的权限
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
class UserC extends BaseC {
    public function __construct() {
        parent::__construct();
        $this->load->model("UserModel");
    }
    public function index(){
        $data["baseInfo"]        = $this->baseInfo;
        $data["menuCatagery"] = "员工";
        $data["menuSub"]      = "员工信息管理";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data);
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('organization/user');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }

    public function newUser(){
        $data["baseInfo"]        = $this->baseInfo;
        $data["menuCatagery"] = "员工";
        $data["menuSub"]      = "新增员工信息";
        $data["menuDetail"]   = "";
        $code = $this->getNewUserCode();
        $user = array("userid"=>"","deptid"=>"","deptid2"=>"","usercode"=>$code,"username"=>"","phone"=>"","email"=>"","gender"=>"","isadmin"=>"");

        if (isset($_REQUEST["id"])){
            $userid = $_REQUEST["id"];
            $userrows = $this->UserModel->getUserInfoById($userid);
            $user = count($userrows)>0?$userrows[0]:$user;
        }
        $data["user"] = $user;
        $this->load->view('common/header', $data);
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('organization/usernew');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function getUserList() {
        $rows   = $this->UserModel->getUserList();
        $count  = count($rows);
        parent::echoLocalBootstrapTableData($rows,$count);
    }
    //获取最新的员工编号
    public function getNewUserCode() {
        $rows   = $this->UserModel->getUserInfoTop();
        $code=1000;
        if (count($rows)>0){
            $code = ((int)$rows[0]["usercode"])+1;
        }
        return $code;
    }

    public function saveUser() {
        $param        = $_REQUEST;
        $params_keys = ["deptid","username","gender","phone","email","isadmin"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        $userid = $param["userid"];
        $param["password"] = "123456";
        $flg = empty($userid)?true:false;
        $isMobileExist = $this->UserModel->isMobileExist($param["phone"],$param["phone2"]);
        if ($isMobileExist){
            parent::echoFail("手机号码已存在，请重新输入");
        }
        // 保存数据
        if (!empty($userid)) {
            $result = $this->UserModel->updateUserInfo($param);
        } else {
            $result = $this->UserModel->saveUserInfo($param);
        }
        if ($result) {
            parent::echoSuccess("保存成功");
        } else {
            parent::echoFail("保存失败");
        }
    }
    public function delUser(){
        $param        = $_REQUEST;
        $params_keys = ["userid"];
        if (!parent::checkParam($params_keys,$param))
            parent::echoFail("缺少参数");
        $result = $this->UserModel->delUser($param);
        if ($result) {
            parent::echoSuccess("删除成功");
        } else {
            parent::echoFail("删除失败");
        }
    } 
}
