<?php

include_once(APP_PATH_L . "DBUtil.php"); 
include_once(APP_PATH_L . "StringUtil.php"); 
include_once(APP_PATH_L . "SysDict.php"); 

class GameLogModel extends CI_Model {

    private $dbName = "default"; //选用的数据库配置名称

    function __construct() {
        parent::__construct();
    }
    //获取游戏统计数据
    function getGameLog($sdate,$edate){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT
                    *
            FROM
                    game_score_report 
            WHERE
                    1 = 1"; 
        if(!empty($sdate) && !empty($edate)){
            $sql.=" AND date between '$sdate' and '$edate' "; 
        }
        $sql.=" ORDER BY date,rno DESC";  
        $query  = $DBData->query($sql);
        return $query->result_array(); 
    }
    
    function getThouseLog($sdate,$edate){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT
                date,
                tno,
                tname,
                `owner`,
                SUM(`create`) as `create`,
                SUM(game) as game,
                SUM(bigwin) as bigwin,
                SUM(paynum) as paynum,
                COUNT(DISTINCT uno) as num
        FROM
                game_teahouse_report
        WHERE 1=1 ";
        $sqlParam = [];
        if(!empty($sdate) && !empty($edate)){
            $sql.=" AND date between '$sdate' and '$edate'"; 
        }
        $sql.=" GROUP BY date, tno ORDER BY date DESC";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array(); 
    }
    function getThouseMonthLog($sdate,$edate){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT
                GTR.tno,
                GTR.tname,
                GTR.`owner`,
                SUM(GTR.`create`) as `create`,
                SUM(GTR.game) as game,
                SUM(GTR.bigwin) as bigwin,
                SUM(GTR.paynum) as paynum,
                COUNT(DISTINCT GTR.uno) as num,
		TMP.num as unum,
		TMP.avgmin 
        FROM
                game_teahouse_report AS GTR
 LEFT JOIN (
		SELECT tno,tname,jucount-roundcount as num,floor(AVG(`minute`)) as avgmin 
		FROM game_score_report WHERE date BETWEEN '$sdate' and '$edate' 
		AND tno is not null
		GROUP BY tno
) AS TMP ON GTR.tno = TMP.tno
        WHERE 1=1 ";
        $sqlParam = [];
        if(!empty($sdate) && !empty($edate)){
            $sql.=" AND GTR.date between '$sdate' and '$edate'"; 
        }
        $sql.=" GROUP BY  GTR.tno ORDER BY GTR.date DESC";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array(); 
    }
    /*
    function getThouseMonthLog($sdate,$edate){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT
                tno,
                tname,
                `owner`,
                SUM(`create`) as `create`,
                SUM(game) as game,
                SUM(bigwin) as bigwin,
                SUM(paynum) as paynum,
                COUNT(DISTINCT uno) as num
        FROM
                game_teahouse_report
        WHERE 1=1 ";
        $sqlParam = [];
        if(!empty($sdate) && !empty($edate)){
            $sql.=" AND date between '$sdate' and '$edate'"; 
        }
        $sql.=" GROUP BY  tno ORDER BY date DESC";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array(); 
    }*/
    function getThouseUserLog($sdate,$edate){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT
                    date,
                    tno,
                    tname,
                    `owner`,
                    uno,
                    uname,
                    `create`,
                    game,
                    bigwin
            FROM
                    game_teahouse_report
            WHERE 1=1 ";
        $sqlParam = [];
        if(!empty($sdate) && !empty($edate)){
            $sql.=" AND date between '$sdate' and '$edate'"; 
        }
        $sql.=" ORDER BY date DESC";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array(); 
    } 
    
    function getGhostLog($sdate,$edate){
        $DBData = $this->load->database($this->dbName, TRUE);
        $sql="SELECT
                    *
            FROM
                    ghost_report 
            WHERE
                    1 = 1";
        $sqlParam = [];
        if(!empty($sdate) && !empty($edate)){
            $sql.=" AND date between '$sdate' and '$edate'"; 
        }
        $sql.=" ORDER BY date DESC";
        $query  = $DBData->query($sql,$sqlParam);
        return $query->result_array(); 
    }
}
