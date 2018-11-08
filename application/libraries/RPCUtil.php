<?php

class RPCUtil {

    public static function sendSMS($mobile, $sms) {
        if ($mobile > 0) {
            header("Content-type: text/html; charset=utf-8");
            $url = "http://utf8.sms.webchinese.cn/?Uid=softshare&Key=f2c8b426087f123ff923&smsMob=$mobile&smsText=$sms";
            return RPCUtil::getRemoteData($url);
        }
        return false;
    }

    public static function getOpenId($appid, $secret, $code) {
        if (empty($code)) return "";

        header("Content-type: text/html; charset=utf-8");
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code ";
        return RPCUtil::getRemoteData($url);
    }

    public static function registerWxMenu($appid, $secret, $menus) {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
        $output = RPCUtil::getRemoteData($url);

        $jsoninfo = json_decode($output, true);
        $access_token = $jsoninfo["access_token"];

        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
        return RPCUtil::https_request($url, $menus);
    }

    public static function https_request($url, $data = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
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
        $output = curl_exec($ch); //执行并获取HTML文档内容
        curl_close($ch); //释放curl句柄
        return $output;
    }

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
    }
}
