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
