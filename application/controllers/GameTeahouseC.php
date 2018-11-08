<?php
/* 
 * 游戏记录管理
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APP_PATH_C . "BaseC.php"); 
include_once(APP_PATH_L . "/PHPExcel/PHPExcel.php"); 

class GameTeahouseC extends BaseC {
    private $thouseData = null; 
    private $tj_jucount = array(0=>"",1=>"",2=>"",3=>"",4=>"",5=>"",5=>"");
    private $tj_paycount = array(0=>"",1=>"",2=>"",3=>"",4=>"",5=>"",5=>"");
    public function __construct() {
        parent::__construct();
        $this->load->model("UserModel"); 
        $this->load->model("RoomcardModel"); 
        $this->load->model("GameLogModel");  
    }
    public function index(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "游戏记录管理";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = "";  
        
        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gamelog/thlog');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    public function getGameLog(){ 
        $limit = parent::getParam("limit");
        $offset = parent::getParam("offset");  
        //获取所有二级代理商的房卡数量
        $sAgentRcData = $this->RoomcardModel->getList(3); 
        $postData = array ("offset"=>$offset,"limit"=>$limit); 
        $gameData = parent::HttpPost("stagame",$postData); 
        $data = json_decode($gameData,true);  
        $count = count($data["result"]); 
        foreach ($data["result"] as $key =>$val) { 
            $uid = $val["uid"];
            $rcount = 0;
            foreach ($sAgentRcData as $value) { 
                if($value["GAMEID"] == $uid){ 
                    $rcount = $value["ROOMCARD"];  
                    break;
                }
            }
            array_push($data["result"][$key], ["rcount"=>$rcount]); 
        }
        parent::echoLocalBootstrapTableData($data["result"], $count);
    }
    
    //活跃数据统计
    public function thactlog(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "活跃数据统计";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gamelog/thActLog');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //茶馆活跃数据
    public function getActivateData(){ 
        $edate = empty($_REQUEST["e"])?date("Y-m-d",time()):$_REQUEST["e"];
        $sdate = empty($_REQUEST["s"])?date("Y-m-d",strtotime("-7 day")):$_REQUEST["s"]; 
        $data = $this->GameLogModel->getThouseLog($sdate,$edate);
        parent::echoLocalBootstrapTableData($data, count($data));
    }
    public function getActivateData_20171228(){
        $limit = parent::getParam("limit");
        $offset = parent::getParam("offset");   
        $edate = empty($_REQUEST["e"])?date("Y-m-d",  time()):$_REQUEST["e"];
        $sdate = empty($_REQUEST["s"])?date("Y-m-d",strtotime("-7 day")):$_REQUEST["s"];
        $edate .= " 23:59";
        $sdate .= " 00:00";
        $postData = array ("offset"=>$offset,"limit"=>$limit,"s"=>$sdate,"e"=>$edate);
        $gameData = parent::HttpPost("stactive",$postData);
        $data = json_decode($gameData,true); 
        $count = count($data["result"]); 
        parent::echoLocalBootstrapTableData($data["result"], $count);
    }
    //茶馆月报表
    public function thMonthLog(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "茶馆月报表";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gamelog/thMonthReport');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //茶馆月报表数据
    public function getThMonthData(){ 
        $firstday=date("Y-m-01",strtotime("-1 month"));//上个月第一天
        $lastday=date("Y-m-t",strtotime("-1 month"));//上个月最后一天
  
        $edate = empty($_REQUEST["e"])?$lastday:$_REQUEST["e"];
        $sdate = empty($_REQUEST["s"])?$firstday:$_REQUEST["s"];  
        
        $data = $this->GameLogModel->getThouseMonthLog($sdate,$edate);
        parent::echoLocalBootstrapTableData($data, count($data));
    }
    
    //排名数据统计
    public function ranklog(){
        $data["agent"]        = $this->agent;
        $data["menuCatagery"] = "排名数据统计";
        $data["menuSub"]      = "";
        $data["menuDetail"]   = ""; 

        $this->load->view('common/header', $data); 
        parent::includeMenu();
        $this->load->view('common/wrapper');
        $this->load->view('gamelog/ranklog');
        $this->load->view('common/modalinfo');
        $this->load->view('common/footer');
    }
    //排名数据
    public function getRankData(){ 
        //默认显示当前的数据
        $edate = empty($_REQUEST["e"])?date("Y-m-d",time()):$_REQUEST["e"];
        $sdate = empty($_REQUEST["s"])?date("Y-m-d",strtotime("-7 day")):$_REQUEST["s"]; 
        $data = $this->GameLogModel->getThouseUserLog($sdate,$edate);
        $thouseData = json_decode(parent::HttpPost("getTeahouseData",[]),true);
        $this->thouseData = $thouseData["result"];
        foreach ($data as $key=>$value) { 
            $pcount = $this->getTpcount($value["tno"]);
            $data[$key]["pcount"] = $pcount; 
        }
        parent::echoLocalBootstrapTableData($data, count($data));
    }
    
    public function getRankData_20171228(){ 
        //默认显示当前的数据
        $edate = empty($_REQUEST["e"])?date("Y-m-d",  time()):$_REQUEST["e"]; 
        $sdate = empty($_REQUEST["s"])?date("Y-m-d", time()):$_REQUEST["s"];
        $edate .= " 23:59";
        $sdate .= " 00:00";  
        $postData = array ("s"=>$sdate,"e"=>$edate);
        $gameData = parent::HttpPost("staugame",$postData);
        $data = json_decode($gameData,true); 
        $count = count($data["result"]); 
        parent::echoLocalBootstrapTableData($data["result"], $count);
    }
    
    //导出本周报表数据
    public function exportReport(){
        $flg = $_REQUEST["flg"];
        //查询本周茶馆数据 
        $sdefaultDate = date("Y-m-d");
        $first = 1;  //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w=date('w',strtotime($sdefaultDate));  
        $week_start=date('Y-m-d',strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days'));//获取本周开始日期，如果$w是0，则表示周日，减去 6 天   
        $week_end=date('Y-m-d',strtotime("$week_start +6 days"));//本周结束日期 
        
        if($flg=="0"){//上周
            $week_start = date('Y-m-d',strtotime("$week_start - 7 days"));  //上周开始日期
            $week_end = date('Y-m-d',strtotime("$week_end - 7 days"));  //上周结束日期
        }
//        print_r($week_start."---------".$week_end);
//        die;
        //获取新增用户数和新增茶馆数
        $ghost = $this->GameLogModel->getGhostLog($week_start,$week_end);
        $data = $this->GameLogModel->getThouseLog($week_start,$week_end);
        //对数据进行处理
        $resArr = array();//数据处理之后的结果集
        foreach ($data as $value) {
            $tno = $value["tno"];
            $tname = $value["tname"];
            $isin = false;  
            foreach ($resArr as $resKey => $resVal) { 
                if($tno."_".$tname==$resKey){ 
                    array_push($resArr[$resKey], $value);
                    $isin = true;
                    break;
                }
            }
            if(!$isin){ 
                $resArr[$tno."_".$tname][0] = $value;
            }  
        }
        $getData = json_decode(parent::HttpPost("getTeahouseData",[]),true);
        $this->thouseData = $getData["result"];
        $objPHPExcel = new PHPExcel();
        //设置属性  
        $objPHPExcel->getProperties()  
            ->setCreator("GHOST")  
            ->setLastModifiedBy("GHOST")  
            ->setTitle("Office 2007 XLSX Test Document")  
            ->setSubject("Office 2007 XLSX Test Document")  
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes."); 
        //3.填充表格  
        $objActSheet = $objPHPExcel->setActiveSheetIndex(0); //填充表头  
        $cline = 7;//茶馆数据从第7行开始
        $line = 6;//一组茶馆数据5行
        $this->setTitle($objActSheet); 
        
        $objActSheet->setCellValue("I2","=SUM(B2:H2)");//周合计 设置公式：新增注册用户数
        $objActSheet->setCellValue("I3","=SUM(B3:H3)");//周合计 设置公式：新增茶馆数
        $objActSheet->setCellValue("I4","=SUM(B4:H4)");//周合计 设置公式：组局数
        $objActSheet->setCellValue("I5","=SUM(B5:H5)");//周合计 设置公式：房卡消耗数
        
        foreach ($ghost as $value){
            $this->setGhostData($objActSheet, $value);
        } 
        foreach ($resArr as $key => $res) {
            $tmpArrs = explode("_",$key); 
            $tname = count($tmpArrs)==2?$tmpArrs[1]:""; 
            $this->setGroupTitle($objActSheet, $cline, $tname);
            foreach ($res as $value) {
                $this->setGroupValue($objActSheet, $cline, $value);
                $objActSheet->setCellValue("I".($cline+2),"=SUM(B".($cline+2).":H".($cline+2).")");//设置公式:活跃人数
                $objActSheet->setCellValue("I".($cline+3),"=SUM(B".($cline+3).":H".($cline+3).")");//设置公式:组局数
                $objActSheet->setCellValue("I".($cline+4),"=SUM(B".($cline+4).":H".($cline+4).")");//设置公式:房卡消耗数
            }
            $cline+=$line;
        } 
        
        foreach ($this->tj_jucount as $key=>$value) {
            $col = $this->getColByWeek($key);
            $value = $value==""?0:$value;
            $objActSheet->setCellValue($col."4","=SUM(".$value.")");//设置公式：新增茶馆数
        }
        
        foreach ($this->tj_paycount as $key=>$value) {
            $col = $this->getColByWeek($key);
            $value = $value==""?0:$value;
            $objActSheet->setCellValue($col."5","=SUM(".$value.")");//设置公式：新增茶馆数
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('酒鬼棋牌运营数据统计');  
        $objPHPExcel->setActiveSheetIndex(0);
        $filename = date("Ymd").'_酒鬼棋牌运营数据统计.xls';  
        ob_end_clean();//清除缓冲区,避免乱码  
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");  
        header('Content-Disposition: attachment;filename='.$filename);  
        header('Cache-Control: max-age=0');   
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');  
        $objWriter->save('php://output');
        return $objWriter;
    }
    //根据tno获取茶馆的人数
    private function getTpcount($tno){
        $count = 0;
        foreach ($this->thouseData as $value) {
            if($tno==$value["tno"]){
                $count = $value["pcount"];
                break;
            }
        }
        return $count;
    }
    //设置标题
    private function setTitle($objActSheet){
        $objActSheet->setCellValue('B1','周一');
        $objActSheet->setCellValue('C1','周二');
        $objActSheet->setCellValue('D1','周三');
        $objActSheet->setCellValue('E1','周四');
        $objActSheet->setCellValue('F1','周五');
        $objActSheet->setCellValue('G1','周六');
        $objActSheet->setCellValue('H1','周日');
        $objActSheet->setCellValue('I1','周合计');
        $objActSheet->setCellValue('A2','新增注册用户数');
        $objActSheet->setCellValue('A3','新增茶馆数');
        $objActSheet->setCellValue('A4','组局数');
        $objActSheet->setCellValue('A5','房卡消耗数'); 
    }
    /**
     * 设置一组数据
     * $objActSheet Excel sheet对象
     * $cline 从第几行开始
     * $value 茶馆信息数组
     */
    private function setGroupTitle($objActSheet,$cline,$tname){
        $objActSheet->setCellValue("A".$cline,$tname);//茶馆名称
        $objActSheet->getStyle("A".$cline)->applyFromArray(array('font' =>array('bold' => true)));
        $objActSheet->setCellValue("A".($cline+1),"人数");
        $objActSheet->setCellValue("A".($cline+2),"活跃人数");
        $objActSheet->setCellValue("A".($cline+3),"组局数");
        $objActSheet->setCellValue("A".($cline+4),"房卡消耗数"); 
    }
    private function setGroupValue($objActSheet,$cline,$value){  
        $w = date("w",strtotime($value["date"]));
        $col = $this->getColByWeek($w);
        $pcount = $this->getTpcount($value["tno"]);
        $objActSheet->setCellValue($col.($cline+1),$pcount);//人数
        $objActSheet->setCellValue($col.($cline+2),$value["num"]);//活跃人数
        $objActSheet->setCellValue($col.($cline+3),$value["game"]);//组局数
        $objActSheet->setCellValue($col.($cline+4),$value["paynum"]);//房卡消耗数 
        $this->tj_jucount[$w] .= $this->tj_jucount[$w]==""?$col.($cline+3):"+".$col.($cline+3);
        $this->tj_paycount[$w] .= $this->tj_paycount[$w]==""?$col.($cline+4):"+".$col.($cline+4); 
    }
    private function setGhostData($objActSheet,$value){  
        $w = date("w",strtotime($value["date"]));
        $col = $this->getColByWeek($w); 
        $objActSheet->setCellValue($col."2",$value["reguser"]);//新注册用户数
        $objActSheet->setCellValue($col."3",$value["newthouse"]);//新增茶馆数 
    } 

    //获取第几列
    private function getColByWeek($w){
        switch ($w){
            case 0:
                return "H"; 
            case 1:
                return "B"; 
            case 2:
                return "C"; 
            case 3:
                return "D"; 
            case 4:
                return "E"; 
            case 5:
                return "F"; 
            case 6:
                return "G";  
        }
    } 
}

