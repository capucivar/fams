<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 

class GhostManModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    }
    //根据GID获取玩家信息
    function getUserInfo($gids=""){ 
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT * FROM AGENT_PLAYER where 1=1 ";
        $sqlParam = [];  
        if(!empty($gids)){
            $sql.=" and gid in (?)";
        }
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array(); 
    }
    //获取元宝流水记录
    function getGoldLog($aid="",$id="",$type="",$btime="",$etime="",$level=""){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT
                    A.*,B.WXNICKNAME,B.`LEVEL`
            FROM
                    AGENT_GOLD_LOG A INNER JOIN AGENT B ON A.AID=B.AID
            WHERE
                    1 = 1 ";
        $param=[];
        if(!empty($aid)){
            $sql.=" AND A.AID = ?";
            array_push($param, $aid);
        }
        if(!empty($id)){
            $sql.=" AND A.ID = ?";
            array_push($param, $id);
        }
        if(!empty($type)){
            $sql.=" AND A.TYPE IN (".$type.") ";
        }else{
            $sql.=" AND A.TYPE IN (30, 31, 32, 33,34,35) ";
        }
        if(!empty($level)){
            if($level=="1")
                $sql.=" AND A.AID = ".SysDict::$SYSTEM_AGENT;
            else
                $sql.=" AND B.LEVEL = ".$level; 
        }
        if(!empty($btime) && !empty($etime)){
            $sql.=" AND A.CTIME BETWEEN ? AND ? ";
            array_push($param, $btime);
            array_push($param, $etime);
        }
        $sql.=" ORDER BY A.AUDITSTATUS ,A.CTIME";
        $query  = $DBData->query($sql,$param);
        return $query->result_array(); 
    }
    
    //添加申请提现记录
    function saveLog($param){
        $DBData   = $this->load->database($this->dbName, TRUE);
        $sql = "INSERT INTO AGENT_GOLD_LOG (ID,AID,TYPE,NUM,CONTENT,AUDITSTATUS,REASON,CTIME,MTIME,ISVALID) VALUES (?,?,?,?,?,?,?,?,1)";
        $sqlParam = array(
            StringUtil::orderid(23,"2100"),
            $param["AID"],
            $param["TYPE"],
            $param["NUM"],
            $param["CONTENT"],
            1,
            $param["REASON"], 
            date("Y-m-d H:i:s", time()),
            date("Y-m-d H:i:s", time()));
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
    //修改审核状态
    function updateState($param){
        $DBData   = $this->load->database($this->dbName, TRUE); 
        $sql="";
        $sqlParam=[];
        if(!empty($param["url"])){ 
            $sql = "UPDATE AGENT_GOLD_LOG SET AUDITSTATUS=?, IMGURL=?, MTIME=? WHERE ID=?";
            $sqlParam = array( 
                $param["state"],
                $param["url"], 
                date("Y-m-d H:i:s", time()),
                $param["id"]);
        }else{
            $sql = "UPDATE AGENT_GOLD_LOG SET AUDITSTATUS=?, REASON=?, MTIME=? WHERE ID=?";
            $sqlParam = array( 
                $param["state"],
                $param["reason"], 
                date("Y-m-d H:i:s", time()),
                $param["id"]);
        } 
        
        //如果是支付操作的话，添加一条平台回收元宝的记录
        if($param["state"]==SysDict::$GOLDAUDITSTATUS["granted"]){
            $sql2 = "INSERT INTO AGENT_GOLD_LOG (ID,AID,TYPE,NUM,CONTENT,AUDITSTATUS,REASON,CTIME,MTIME,ISVALID) VALUES (?,?,?,?,?,?,?,?,?,1)";
            $sqlParam2 = array(
                StringUtil::orderid(23,  SysDict::$EXUID["sys_gold_recycle"]),
                SysDict::$SYSTEM_AGENT,
                SysDict::$LOGTYPE["sys_gold_recycle"],
                $param["num"],
                "平台回收元宝",
                0,
                "", 
                date("Y-m-d H:i:s", time()),
                date("Y-m-d H:i:s", time()));
            
            $sql3  = " UPDATE `AGENT` SET `GOLD`=`GOLD`+? ,`MTIME`=?  WHERE `AID`=?  and STATE=?";
            $sqlParam3 = [(int)$param["num"],date("Y-m-d H:i:s", time()),  SysDict::$SYSTEM_AGENT,SysDict::$SYSTEM_AGENT_STATE ]; 

            //事务提交
            $DBData->trans_start(); 
            $DBData->query($sql, $sqlParam);
            $DBData->query($sql2, $sqlParam2);
            $DBData->query($sql3, $sqlParam3);
            $DBData->trans_complete(); 
            return $DBData->trans_status(); 
        }
        $DBData->query($sql, $sqlParam);
        return $DBData->affected_rows() == 1;
    }
}
