<?php
/**
 * 下级代理商
 * **/
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "SysDict.php");

class RoomcardC extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->model("RoomcardModel");
        $this->load->model("LogModel");
    }
    
    public function index() {
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "房卡充值";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 
        $data["aid"] = $this->agent["AID"];
        $this->load->view('common/header', $data);
//        $this->load->view('common/menu');
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('rcard');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function go(){
        $num = parent::getParam("cardnum");
        $money = parent::getParam("money");
        $pay = parent::getParam("pay");
        $aid = $this->agent["AID"];
//        $pay = 0.01;
        $_SESSION['total_fee'] = $pay;//总金额
        $_SESSION['body']=$aid."- 房卡充值";//商品描述
        $_SESSION['money']=$money;//商品描述
        $_SESSION['pay']=$pay;//实际支付
        $_SESSION['num']=$num;//房卡数量
        $_SESSION['aid']=$num;//房卡数量
        Header("Location: http://dls.dangmianyou.com/wx/js_api_call.php");
    }

    //房卡充值并生成房卡
    public function createRC() {
        $num = parent::getParam("cardnum");
        $money = parent::getParam("money");
        $pay = parent::getParam("pay");
        $aid = $this->agent["AID"];  
        
        $res = $this->RoomcardModel->saveRoomCard($aid,$num);
        if($res){
             //写入日志
            $logParam["AID"]=$aid;
            $logParam["LTYPE"] = SysDict::$LOGTYPE["rcharge"];
            $logParam["RCOUNT"] = $num;
            $logParam["RCTO"] = $aid;
            $logParam["MONEY"] = $money; 
            $logParam["ACTUALPAY"] = $pay;
            $logParam["AREA"] = "";
            $logParam["RCLEVEL"] = $this->agent["LEVEL"]+1;
            $this->LogModel->saveRcLog($logParam);
            parent::echoSuccess("房卡充值成功");
        }else{
            parent::echoSuccess("房卡充值失败");
        }
    }
}
