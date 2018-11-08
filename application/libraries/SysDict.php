<?php

class SysDict {

    public static $AUTH_COOKIE_NAME = "DA_AUTH";
    public static $AUTH_COOKIE_ADMIN_NAME = "DA_AUTH_ADMIN";
    public static $AUTH_COOKIE_CLIENT_NAME = "DA_AUTH_CLIENT";
    public static $SYSTEM_AGENT = 999999;
    public static $SYSTEM_AGENT_STATE = 9;
    public static $SYSTEM_AGENT_LEVEL = 9;
    
    public static $USERSTATE = [
        "invalid" => 0, 
        "valid"  => 1
    ];

    public static $ISVALID = [
        "valid"   => 1,
        "invalid" => 0
    ];
    
    public static $NOTICELEVEL = [
        "system" => 1,//系统公告
        "lower"=> 2//对下级代理商发起的消息
    ];
    public static $ANGENTLEVEL = [
        "admin"=>0,//系统管理员
        "head" => 1,//总代
        "one" => 2,//一级代理商
        "two" => 3,//二级代理商
        "sys" => 9,//平台
        "player" => 10,//玩家
    ]; 
    public static $LOGTYPE = [
        "default" => 0,
        "login" => 10,
        "pwd" => 11,
        "phone" => 12,
        "prederc" => 13,//预扣
        "derc" => 14,
        "cancelderc" => 15,
        "winnerpay" => 16,//代开房费，大赢家后付费 
        "teashoppay" => 17,//茶馆模式 
        "rcharge" => 20,//代理商房卡充值
        "rctransfer" => 21,//房卡转账：渠道代理商转给玩家代理商
        "sys_new_card" => 22,//平台生成赠送房卡
        "gamerpay" => 23,//玩家充值
        "giverc"=>24,//赠送给玩家：完成任务赠送
        "rctransfer_player" => 25,//房卡转账：玩家代理商转给玩家
        "get_transfer_card" => 26,//收到转账：玩家代理商收到转账的房卡
        "get_transfer_card_player" => 27,//收到转账：玩家收到玩家代理商的转账
        "sys_give_card" => 28,//平台赠送
        "get_sys_give_card" => 29,//获得赠送
        
        //元宝记录
        "goldearn" => 30,//代开房卡收益
        "grouptrust" => 31,//代开房卡托管费
        "groupsub" => 32,//组局补贴
        "withcash" => 33,//元宝提现
        "sys_gold_recycle" => 34,//平台回收元宝
        "sys_gold_pay" => 35,//平台支付元宝 
        
        "banid" => 40,//封号
        "unbanid" => 41,//解封
        "unbind" => 42,//解绑代理商和玩家关系
    ]; 
    public static $PAYTYPE=[
        "default" => 1,
        "winnerpay" =>2//扣除代理商房卡，待大赢家支付
    ];

    //元宝审核状态
    public static $GOLDAUDITSTATUS=[ 
        "submitted" =>1,//已提交待审核
        "audited" =>2,//已审核通过待发放
        "granted" =>3,//已发放请查收
        "refuse" => 4,//拒绝
        "archive" => 9,//归档
    ];
    //流水号的前缀
    public static $EXUID=[ 
        "default" => 9000,
        "sys_new_card" => 1000,//平台生成赠送房卡
        "transfer_card" => 1100,//房卡转账
        "get_transfer_card" => 1110,//收到转账
        "sys_give_card" =>1200,//平台赠送
        "get_sys_give_card" =>1210,//收到平台赠送
        "agent_rcharge_card" =>1300,//代理商充值
        "player_rcharge_card" =>1310,//玩家充值
        
        "goldearn" =>2000,//平台回收元宝
        "goldwcash" =>2100,//元宝提现记录
        "sys_gold_recycle" =>2200,//平台回收元宝 
        "sys_gold_pay" =>2210,//平台支付元宝 
        
        "cardpay"=>3000,//消费房卡
        "agent_cardpay"=>3100,//代理商代扣
        "tea_cardpay" =>3200,//茶馆模式消费房卡
    ];
    public static function GET_LOG_TYPE($type) {
        switch ($type) { 
            case SysDict::$LOGTYPE["derc"]:
                return "房卡消费";
            case SysDict::$LOGTYPE["rcharge"]:
                return "代理商充值";
            case SysDict::$LOGTYPE["rctransfer"]:
                return "房卡转账";
            case SysDict::$LOGTYPE["sys_new_card"]:
                return "平台生成赠送房卡";
            case SysDict::$LOGTYPE["gamerpay"]:
                return "玩家充值";
            case SysDict::$LOGTYPE["giverc"]:
                return "赠送给玩家";
            case SysDict::$LOGTYPE["rctransfer_player"]:
                return "房卡转账";
            case SysDict::$LOGTYPE["get_transfer_card"]:
                return "收到房卡转账";
            case SysDict::$LOGTYPE["get_transfer_card_player"]:
                return "收到房卡转账";
            case SysDict::$LOGTYPE["sys_give_card"]:
                return "平台赠送";
            case SysDict::$LOGTYPE["get_sys_give_card"]:
                return "获得赠送";
        }
    }
    public static function GET_AGENT_LEVEL($type) {
        switch ($type) {
            case "0":
                return "系统管理员";
            case "1":
                return "总代";
            case "2":
                return "渠道代理商";
            case "3":
                return "玩家代理商"; 
            case "9":
                return "平台";
        }
    }
    //根据房卡消费数量获取元宝收益
    public static function GET_GOLD_NUM($num) {
        switch ($num) {
            case 2:
                return 1;
            case 3:
                return 1.5;
            case 4:
                return 2;
            default:
                return 0;
        }
    }
}