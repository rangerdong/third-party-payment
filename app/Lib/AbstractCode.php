<?php
namespace App\Lib;

abstract class AbstractCode
{
    abstract public static function errMsg();

    public static function getErrorMsg($code)
    {
        $errArr = static::errMsg();
        if ( ! array_key_exists($code, $errArr)) {
            return $code;
        }
        return $errArr[$code];
    }
}
