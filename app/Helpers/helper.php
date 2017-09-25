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
