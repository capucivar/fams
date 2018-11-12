<?php

class SysDict {

    public static $AUTH_COOKIE_NAME = "DA_AUTH";
    public static $AUTH_COOKIE_ADMIN_NAME = "DA_AUTH_ADMIN";
    public static $AUTH_COOKIE_CLIENT_NAME = "DA_AUTH_CLIENT"; 
    
    public static $USERSTATE = [
        "invalid" => 0, 
        "valid"  => 1
    ];

    public static $ISVALID = [
        "valid"   => 1,
        "invalid" => 0
    ];
    public static $GENDER = [
        "unknow"   => 0,
        "man" => 0,
        "woman" => 1
    ]; 
    public static function GET_GENDER($gender){
        switch ($gender){
            case 0:
                return "未知"; 
            case 1:
                return "男"; 
            case 2:
                return "女"; 
        }
    }
}