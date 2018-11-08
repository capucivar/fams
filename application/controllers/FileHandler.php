<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "StringUtil.php");  
include_once(APP_PATH_L . "FileUtil.php");

class FileHandler extends BaseC {

    public function __construct() {
        parent::__construct();
        $this->load->helper('cookie');
    }

    public function uploadFile($inputname,$filename="") { 
        if(empty($filename) || $filename=="0")
            $filename = strtoupper(md5(uniqid(mt_rand(), true))); 
        if ($_FILES[$inputname]["type"] != "image/png" && $_FILES[$inputname]["type"] != "image/jpg" && $_FILES[$inputname]["type"] != "image/jpeg") 
            parent::echoFail("文件类型不合格");
        
        if ($_FILES[$inputname]["size"] > 2000000) parent::echoFail("文件过大");

        $relaPath     = "/static/archive/wcash/";
        $savePath     = APP_PATH . $relaPath; 
        $saveFileName = $savePath . $filename . ".jpg"; 
        FileUtil::mkdirs($savePath);

        //接收和保存文件
        move_uploaded_file($_FILES[$inputname]["tmp_name"], $saveFileName);
        chmod($saveFileName, 0777);

        if (!is_file($saveFileName)) parent::echoFail("服务器保存图片失败"); 
        parent::echoSuccess($filename.".jpg");
    }
}
