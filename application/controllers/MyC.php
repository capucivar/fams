<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once(APP_PATH_L . "SysDict.php");
//error_reporting(0);

class MyC extends CI_Controller {

    protected $agent = null;
    private $MODE = "local";//local：本地，debug：测试，release：线上
    protected $GHOST_LOCAL = "http://192.168.0.120:8086/";
    protected $GHOST_DEV = "http://ghost4.dangmianyou.com/";
    protected $GHOST = "http://ghost.dangmianyou.com/";
    
    protected $GHOSTMAN_LOCAL = "http://192.168.0.120:8087/";
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取请求的参数
     */
    public function getParam($name) {
        return !isset($_REQUEST[$name]) ? "" : $_REQUEST[$name];
    }

    /**
     * 打印执行「成功」的返回结果
     */
    public function echoSuccess($result,$msg="") {
        $this->setHeader();
        $retAry["code"]    = 1;
        $retAry["message"] = $msg;
        $retAry["result"]  = $result;
        echo json_encode($retAry);
        exit;
    }

    /**
     * 打印执行「失败」的返回结果
     */
    public function echoFail($message) {
        $this->setHeader();
        $retAry["code"]    = 0;
        $retAry["message"] = $message;
        $retAry["result"]  = "";
        echo json_encode($retAry);
        exit;
    }

    /**
     * 打印 BootstrapTable 需要的格式的数据
     */
    public function echoBootstrapTableData($rows, $count) {
        $result["total"] = $count;
        $result["rows"]  = $rows;
        echo json_encode($result);
        exit;
    }
    //本地分页
    public function echoLocalBootstrapTableData($rows, $count) {
        $result["total"] = $count;
        $result["data"]  = $rows;
        echo json_encode($result);
        exit;
    }

    #region 私有方法

    /**
     *设置返回内容的header
     */
    private function setHeader() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header("Content-Type: application/json; charset=utf-8");
    }

    #endregion
    
    public function includeMenu(){
        $level = $this->agent["LEVEL"];
        if($level==SysDict::$ANGENTLEVEL["admin"]){//管理员
            $this->load->view('common/menu0'); 
        }else if($level==SysDict::$ANGENTLEVEL["head"]){//总代
            $this->load->view('common/menu'); 
        }else if($level==SysDict::$ANGENTLEVEL["one"]){//一代
            $this->load->view('common/menu1'); 
        } else if($level==SysDict::$ANGENTLEVEL["two"]){//二代
            $this->load->view('common/menu2'); 
        }else{
            $this->load->view('common/menud'); 
        }
    }
    
    public function HttpPost($funcName,$post_data=null,$url=""){
        if($url==""){ 
//          $url = "http://ghost.dangmianyou.com/".$funcName;//正式
//          $url = "http://ghost4.dangmianyou.com/".$funcName; //测试
            $url = $this->GHOST;
        }
        $url.= $funcName;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
//        print_r($output); 
        return $output;
    }
    public function HttpGet($funcName){ 
        $url = "http://ghost.dangmianyou.com/".$funcName;//正式 
//        $url = "http://ghost4.dangmianyou.com/".$funcName; //测试
//        $url = "http://192.168.0.120:8092/".$funcName; 
        return file_get_contents($url);
    }

    //是否为系统管理员
    public function isAdmin($level){
        if($level!=SysDict::$ANGENTLEVEL["admin"])
            return false;
        return true;
    }
    //是否为总代
    public function isHead($level){
        if($level!=SysDict::$ANGENTLEVEL["head"])
            return false;
        return true;
    }
}