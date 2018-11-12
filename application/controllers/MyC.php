<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once(APP_PATH_L . "SysDict.php");
//error_reporting(0);

class MyC extends CI_Controller {

    protected $baseInfo = null;
    protected $agent = null;
 
    public function __construct() {
        parent::__construct();
    }

    /**
     * 检查参数
     */
    public function checkParam($checkParam,$request) {
//        return !isset($_REQUEST[$name]) ? "" : $_REQUEST[$name];
        foreach ($checkParam as $key){
            if (!array_key_exists($key,$request))
                return false;
        }
        return true;
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
}