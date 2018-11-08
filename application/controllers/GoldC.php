<?php
/* 
 * 元宝记录管理
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");
class GoldC extends BaseC {
    public function __construct() {
        parent::__construct();
        $this->load->model("GoldModel"); 
    }
    public function index(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "元宝管理";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gold/goldLog');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function wcashlog(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "元宝管理";
        $data["menuSub"]      = "提现记录";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gold/wcashlog');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
     public function wcash(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "元宝管理";
        $data["menuSub"]      = "申请提现";
        $data["menuDetail"]   = ""; 

        $data["goldnum"] = $this->agent["GOLD"];
        
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gold/wcash');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    } 
    
    public function getList(){ 
        $aid    = $this->agent["AID"]; 
        $rows   = $this->GoldModel->getGoldLog($aid);
        $count = count($rows);  
        parent::echoLocalBootstrapTableData($rows, $count);
    }
    //提现记录
    public function getCashList(){ 
        $aid    = $this->agent["AID"];
        $level = $this->agent["LEVEL"];
        if(parent::isAdmin($level) || parent::isHead($level))
            $aid="";
        
        $rows   = $this->GoldModel->getGoldLog($aid, "",SysDict::$LOGTYPE["withcash"]);
        $count = count($rows);  
        parent::echoLocalBootstrapTableData($rows, $count);
    }
    //添加申请记录
    public function addWcashLog(){
        $param  = $_REQUEST; 
        if(empty($param))
            parent::echoFail("参数缺失");
        if(empty($param["NUM"]))
            parent::echoFail("参数NUM缺失");
        if(empty($param["REASON"]))
            parent::echoFail("参数REASON缺失");
        $param["AID"] = $this->agent["AID"];
        $param["TYPE"] = SysDict::$LOGTYPE["withcash"];
        $param["CONTENT"] = ""; 
        $result = $this->GoldModel->saveLog($param);
        if ($result) {
            parent::echoSuccess("","申请已成功提交，请耐心等待管理员审核");
        } else {
            parent::echoFail("申请提交失败");
        }
    }
    
    //系统管理员 元宝订单管理
    public function wcashOrder(){
        $level = $this->agent["LEVEL"];
        if(!parent::isAdmin($level) && !parent::isHead($level))
            parent::echoFail ("没有权限");
        
         $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "元宝管理";
        $data["menuSub"]      = "订单管理";
        $data["menuDetail"]   = ""; 
        
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gold/wcashLogAdmin');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //元宝订单详情
    public function wcashOrderDetail(){
        $level = $this->agent["LEVEL"];
        if(!parent::isAdmin($level) && !parent::isHead($level))
            parent::echoFail ("没有权限");
        
         $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "元宝管理";
        $data["menuSub"]      = "订单管理";
        $data["menuDetail"]   = ""; 
        
        $id = $_REQUEST["id"]; 
        $rows   = $this->GoldModel->getGoldLog("", $id);
        if(count($rows)!=1)
            parent::echoFail ("数据错误"); 
        $data["info"] = $rows[0];
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gold/wcashLogDetailAdmin');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //提交归档
    public function archive(){ 
        $param = $_REQUEST;
        $res = $this->GoldModel->updateState($param);
        if($res)
            parent::echoSuccess ("");
        parent::echoFail("操作失败");
    }
    
    //管理员：元宝记录
    public function goldLog(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "元宝管理";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gold/goldLogAdmin');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function getAdminList(){ 
        //查询最近七天的数据 
        $level = empty($_REQUEST["level"])?"":$_REQUEST["level"];
        $edate = empty($_REQUEST["e"])?date("Y-m-d",  time()):$_REQUEST["e"];
        $sdate = empty($_REQUEST["s"])?date("Y-m-d",strtotime("-7 day")):$_REQUEST["s"];
        $edate .= " 23:59";
        $sdate .= " 00:00";
//        $edate = date("Y-m-d",  time())." 23:59";
//        $bdate = date("Y-m-d",strtotime("-7 day"))." 00:00";
        $rows   = $this->GoldModel->getGoldLog("","","",$sdate,$edate,$level);
        $count = count($rows);  
        parent::echoLocalBootstrapTableData($rows, $count);
    }
    
    //收益明细
    public function earn(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "元宝管理";
        $data["menuSub"]      = "收益明细";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gold/earndetail');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //获取收益明细
    public function getEarnList(){
        $rows   = $this->GoldModel->getEarnList($this->agent["AID"]);
        $count = count($rows);  
        parent::echoLocalBootstrapTableData($rows, $count);
    }
}

