<?php
namespace App\Lib;

abstract class AbstractCode
{
    abstract public static function errMsg();

    abstract public static function getErrorMsg($code);
}
