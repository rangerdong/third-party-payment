<?php
namespace App\Lib;

abstract class AbstractStatus
{
    abstract public static function getMap():array ;

    public static function getStatusFromMap($code)
    {
        $map = static::getMap();
        if ( ! array_key_exists($code, $map)) {
            return $code;
        }
        return $map[$code];

    }
}
