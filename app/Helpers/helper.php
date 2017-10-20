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

/**
 * curl抓取网页方法
 *
 * @param string $url 目标网址
 * @param string $type 请求类型
 * @param array $data 传输数据
 * @param int $ssl 是否是https网关
 * @return array $output 返回内容  [body]页面内容 [http_code]是响应码
 */
function curlHttp($url, $data=[], $type='get', $ssl=1):array
{
    $output = [];
    $ch = curl_init();
    $data = is_array($data) ? http_build_query($data) : $data;
    if ($type == 'get') {
        $url = $url . '?' . $data;
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($type == 'post') {
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    if ($ssl) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_BINARYTRANSFER,1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36');
    $res = curl_exec($ch);
    $output['body'] = $res === false ? 'CURL RETURN ERROR:' . curl_error($ch) : $res;
    $output['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $output;
}
