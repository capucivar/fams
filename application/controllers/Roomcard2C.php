<?php
session_start();
/**
 * 下级代理商
 * **/
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "SysDict.php");
include_once(APP_PATH_L . "JSSDK.php");

class Roomcard2C extends BaseC {

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
        $jssdk = new JSSDK("wx54715360b6452c3f", "a79caec0b709da41624d81176701dfc4");
        $data["signPackage"]  = $jssdk->getSignPackage(); 
//        print_r($data["signPackage"]);
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('rcard2');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
        /*
        $arr = $jssdk->getSignPackage();
        print_r($arr); 
        $appId = $arr["appId"];
        $jsapiTicket = $arr["jsapi_ticket"];
        $nonceStr= $arr["noncestr"];
        $timestamp = $arr["timestamp"];
        $url = $arr["url"];
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        echo "<br/><br/><br/>".$string."<br/><br/><br/>";
        
         $signature = sha1($string); 
         echo $signature; 
         */
    }
    
    public function go(){
        $num = parent::getParam("cardnum");
        $money = parent::getParam("money");
        $pay = parent::getParam("pay");
        $aid = $this->agent["AID"];
        $pay=0.01;
        $_SESSION['total_fee'] = $pay;//总金额
        $_SESSION['body']=$aid."- 房卡充值 - ".$num;//商品描述
        $_SESSION['money']=$money;//商品描述
        $_SESSION['pay']=$pay;//实际支付
        $_SESSION['num']=$num; 
        $_SESSION['aid']=$aid;  
        Header("Location: http://dls.dangmianyou.com/wx/js_api_call.php");
    }

    //房卡充值并生成房卡
    public function createRC() {
//        $num = parent::getParam("cardnum");
//        $money = parent::getParam("money");
//        $pay = parent::getParam("pay");
        $num = $_SESSION["num"];
        $money = $_SESSION["money"];
        $pay = $_SESSION["pay"];
        $aid = $this->agent["AID"];
        $res = $this->RoomcardModel->saveRoomCard($aid,$num);
        if($res){
             //写入日志
            $logParam["ID"]=StringUtil::orderid(23, SysDict::$EXUID["agent_rcharge_card"]);
            $logParam["OPERATOR"]=$aid;
            $logParam["TYPE"] = SysDict::$LOGTYPE["rcharge"];
            $logParam["NUM"] = $num;
            $logParam["AID"]=$aid;
            $logParam["TOAID"] = "";
            $logParam["MONEY"] = $money; 
            $logParam["ACTUALPAY"] = $pay;
            $logParam["AREA"] = ""; 
            $res = $this->LogModel->saveRcLog($logParam);
            if($res){
                //记录日志
                $content["msg"]="代理商充值";
                $content["num"]=$num; 
                $data["LTYPE"]= SysDict::$LOGTYPE["rcharge"];//代理商充值
                $data["AID"]= $aid;
                $data["CONTENT"] = json_encode($content);
                $this->LogModel->saveLog($data);
            }
            echo "<script>window.location = '/Home' ;</script>";
        }else{
            echo "<script>alert('房卡初始化失败，请联系管理员');</script>";
        }
    }
}
