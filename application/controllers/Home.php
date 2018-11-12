<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php");

class Home extends BaseC {

    public function __construct() {
        parent::__construct(); 
        $this->load->model("UserModel"); 
    }

    public function index(){ 
        $data["baseInfo"]        = $this->baseInfo;  
        $data["menuCatagery"] = "主页";
        $data["menuSub"]      = "账户";
        $data["menuDetail"]   = "账户概览";   
        $this->load->view('common/header', $data);
        $this->load->view('common/menu');
        $this->load->view('common/wrapper');
        $this->load->view('account');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    
    public function delCookie() {
        delete_cookie(SysDict::$AUTH_COOKIE_NAME);
    }

    public function login() {
        $this->load->view('login');
    }

    public function signout() {
        delete_cookie(SysDict::$AUTH_COOKIE_NAME);
        $this->login(); 
    }
}
