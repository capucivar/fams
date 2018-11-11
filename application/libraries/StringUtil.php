<?php

class StringUtil {

    public static function buildUID($uid) {
        $uid = crc32($uid);
        $uid = (float)sprintf('%u', $uid);
        return $uid;
    }

    public static function uuid($prefix="") {
        $uid = StringUtil::create_guid(); 
        $uid = crc32($uid); 
        $uid = (float)sprintf('%u', $uid);
        return $prefix.$uid;
    }
    //根据时间+随机数生成唯一码
    public static function orderid($length=19,$ex=""){
        $length = $length>24?24:$length;
        $len = $length - 14 - strlen($ex);
        $max = "9"; 
        for($i=0;$i<$len-1;$i++){
            $max.="9";
        }
        return $ex.date('YmdHis').str_pad(mt_rand(1, (int)$max), $len, '0', STR_PAD_LEFT);
    }

    public static function create_guid() {
        $microTime = microtime(); 
        list($a_dec, $a_sec) = explode(" ", $microTime); 
        $dec_hex = dechex($a_dec * 1000000);  
        $sec_hex = dechex($a_sec); 
        StringUtil::ensure_length($dec_hex, 5); 
        StringUtil::ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex; 
        $guid .= StringUtil::create_guid_section(3);
        $guid .= '-';
        $guid .= StringUtil::create_guid_section(4);
        $guid .= '-';
        $guid .= StringUtil::create_guid_section(4);
        $guid .= '-';
        $guid .= StringUtil::create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= StringUtil::create_guid_section(6);
        return $guid;
    }

    public static function create_guid_section($characters) {
        $return = "";
        for ($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0, 15));
        }
        return $return;
    }

    public static function ensure_length(&$string, $length) {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, "0");
        } else if ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }

    public static function do_mencrypt($input, $key) {
        $input = str_replace("\n", "", $input);
        $input = str_replace("\t", "", $input);
        $input = str_replace("\r", "", $input);
        $key   = substr(md5($key), 0, 24);
        $td    = mcrypt_module_open('tripledes', '', 'ecb', '');
        $iv    = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $encrypted_data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return trim(chop(base64_encode($encrypted_data)));
    }

    //$input - stuff to decrypt
    //$key - the secret key to use

    public static function do_mdecrypt($input, $key) {
        $input = str_replace("\n", "", $input);
        $input = str_replace("\t", "", $input);
        $input = str_replace("\r", "", $input);
        $input = trim(chop(base64_decode($input)));
        $td    = mcrypt_module_open('tripledes', '', 'ecb', '');
        $key   = substr(md5($key), 0, 24);
        $iv    = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $decrypted_data = mdecrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return trim(chop($decrypted_data));
    }

    public static function substring($str, $count) {
        if (strlen($str) > $count)
            $str = mb_substr($str, 1, $count, "UTF-8") . '...';
        return $str;
    }

    public static function substringBeforeFirst($str, $check) {
        return substr($str, 0, stripos($str, $check));
    }

    public static function substringAfterLast($str, $check) {
        return substr($str, strrpos($str, $check) + 1, strlen($str));
    }

    public static function left($str, $num) {
        return substr($str, 0, $num);
    }

    public static function right($str, $num) {
        return substr($str, strlen($str) - $num, strlen($str));
    }

    public static function formatFormData($data, $clearTag) {
        $newArr = null;
        foreach ($data as $key => $value) {
            if (StringUtil::left($key, 1) != $clearTag && $key != "wmy_sessionid") {
                $newArr[$key] = $value;
            }
        }
        return $newArr;
    }

    public static function getRandomCode($count) {
        srand((double)microtime() * 1000000); //create a random number feed.
        $ychar   = "0,1,2,6,8,9";
        $authnum = '';
        $list    = explode(",", $ychar);
        for ($i = 0; $i < $count; $i++) {
            $randnum = rand(0, 5); // 10+26; 这里只有5个数字
            $authnum .= $list[$randnum];
        }
        return $authnum;
    }
    
    public static function getClientIP() {  
        global $ip;  
        if (getenv("HTTP_CLIENT_IP"))  
            $ip = getenv("HTTP_CLIENT_IP");  
        else if(getenv("HTTP_X_FORWARDED_FOR"))  
            $ip = getenv("HTTP_X_FORWARDED_FOR");  
        else if(getenv("REMOTE_ADDR"))  
            $ip = getenv("REMOTE_ADDR");  
        else $ip = "Unknow";  
        return $ip;  
    }
    
    public static function randStr($len=6,$format='ALL') { 
        switch($format) { 
            case 'ALL':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; break;
            case 'CHAR':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~'; break;
            case 'NUMBER':
                $chars='0123456789'; break;
            default :
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; 
            break;
        }
        mt_srand((double)microtime()*1000000*getmypid()); 
        $ran_str="";
        $i=0;
        while(strlen($ran_str)<$len){
            //第一位不允许为0
            if($format=="NUMBER" && $i==0)
                $ran_str.=substr("123456789",(mt_rand()%strlen("123456789")),1); 
            else
                $ran_str.=substr($chars,(mt_rand()%strlen($chars)),1); 
            $i++;
        }
        return $ran_str; 
    }
    
    public static function getHttpHost(){
        return "http://dls.dangmianyou.com";
//        return "http://192.168.0.120:8091";
    }
}

class RPCUtil {
    public static function sendSMS($mobile, $sms) {
        if ($mobile > 0) {
            header("Content-type: text/html; charset=utf-8");
            $url = "http://utf8.sms.webchinese.cn/?Uid=softshare&Key=f2c8b426087f123ff923&smsMob=$mobile&smsText=$sms";
            return RPCUtil::getRemoteData($url);
        }
    }

    public static function postRemoteData($rpcRootUrl, $data) {
        $ch = curl_init(); //初始化 
        $url = $rpcRootUrl . "?"; 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true); 
        $post_data = '';
        if ($data != "") {
            foreach ($data as $key => $value) {
                $post_data = $post_data . "&" . $key . "=" . urlencode($value);
            } 
            $post_data = substr($post_data, 1, strlen($post_data) - 1);
        } 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch); //执行并获取HTML文档内容
        curl_close($ch); //释放curl句柄
        return $output;
    }

    public static function getRemoteData($url) {
        $ch = curl_init(); //初始化
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $output = curl_exec($ch); //执行并获取HTML文档内容
        curl_close($ch); //释放curl句柄
        return $output;
    }
/*
    public static function sendRegCode($mobile, $sms) {
        $file_contents = '';
        $url = "http://utf8.sms.webchinese.cn/?Uid=softshare&Key=f2c8b426087f123ff923&smsMob=$mobile&smsText=$sms";
        if (function_exists('file_get_contents')) {
            $file_contents = file_get_contents($url);
        } else {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
        }
        return $file_contents;
    }*/
}