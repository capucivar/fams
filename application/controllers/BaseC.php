<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//error_reporting(0);
include_once(APP_PATH_C . "MyC.php");
include_once(APP_PATH_L . "SysDict.php");

class BaseC extends MyC {

    protected $user = null;

    public function __construct() {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->model('LoginModel');

        $token = get_cookie(SysDict::$AUTH_COOKIE_NAME);
       
        // 不需要验证身份的页面
        $passUrls = [
            "/LoginC/login",
            "/LoginC/doLogin"
        ];
        foreach ($passUrls as $url) {
            if (strpos($_SERVER["REQUEST_URI"], $url) === 0) return;
        }
        
        // 验证身份
        $user = $this->LoginModel->getUserData($token); 
        if (empty($user)) {
            echo "<script type='text/javascript'>window.location.href='/LoginC/login';</script>";
            return;
        } 
        $this->user = $user;  
        // 如果当前账户已冻结，并且当前页面不是冻结页面，则跳转到冻结信息提示页面
        if ($this->user["state"] == SysDict::$USERSTATE["invalid"]
            && strpos($_SERVER["REQUEST_URI"], "/LoginC/invalid") == false
           ){
            if ($_SERVER["REQUEST_URI"] == "/LoginC/invalid") return;

            echo "<script type='text/javascript'>window.location.href='/LoginC/invalid';</script>";
            return;
        } 
    }
}