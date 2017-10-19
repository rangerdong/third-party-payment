<?php

use Illuminate\Support\Facades\Hash;

if (! function_exists('generateCode')) {
    function generateCode($password)
    {
        $random_str = uniqid() . '|' . $password;
        $time_str = substr(time(), 6, -1);
        return strtoupper(substr(md5($random_str.$time_str), 0, 15));
    }
}

if (! function_exists('generateKey')) {
    function generateKey($code)
    {
        return Hash::make($code);
    }
}

if (! function_exists('generateAppCode')) {
    /**
     * @param  $classify
     *
     * @return string
     *
     */
    function generateAppCode($classify)
    {
        $prefix = '';
        switch ($classify) {
            case 0:
                $prefix = 'WEB'; break;
            case 1:
                $prefix = 'AD'; break;
            case 2:
                $prefix = 'IOS'; break;
            default:break;
        }
        $salt = uniqid().time();
        $str = md5($salt);
        return $prefix.substr($str, 0, -strlen($prefix));
    }
}


if(!function_exists('curlRequest')){
    function curlRequest($url, $postData = array(), $launch = 'post', $contentType = 'text/html') {
        $result = "";
        try {
            $header = array("Content-Type:" . $contentType . ";charset=utf-8");
            if (!empty($_SERVER['HTTP_USER_AGENT'])) {		//是否有user_agent信息
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
            }
            $cur = curl_init();
            curl_setopt($cur, CURLOPT_URL, $url);
            curl_setopt($cur, CURLOPT_HEADER, 0);
            curl_setopt($cur, CURLOPT_HTTPHEADER, $header);
            curl_setopt($cur, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($cur, CURLOPT_TIMEOUT, 30);
            //https
            curl_setopt($cur, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($cur, CURLOPT_SSL_VERIFYHOST, FALSE);
            if (isset($user_agent)) {
                curl_setopt($cur, CURLOPT_USERAGENT, $user_agent);
            }
            curl_setopt($cur, CURLOPT_ENCODING, 'gzip');
            if (is_array($postData)) {
                if ($postData && count($postData) > 0) {
                    $params = http_build_query($postData);
                    if ($launch=='get') {		//发送方式选择
                        curl_setopt($cur, CURLOPT_HTTPGET, $params);
                    } else {
                        curl_setopt($cur, CURLOPT_POST, true);
                        curl_setopt($cur, CURLOPT_POSTFIELDS, $params);
                    }
                }
            } else {
                if (!empty($postData)) {
                    $params = $postData;
                    if ($launch=='post') {
                        curl_setopt($cur, CURLOPT_POST, true);
                        curl_setopt($cur, CURLOPT_POSTFIELDS, $params);
                    }
                }
            }
            $result = curl_exec($cur);
            curl_close($cur);
        } catch (Exception $e) {

        }
        return $result;
    }
}
