<?php
namespace App\Lib;

abstract class AbstractMap
{

    abstract public static function getMap():array ;

    /**
     * @param null $code
     *
     * @return string|null
     */
    public static function getNameFromMap($code)
    {
        $map = static::getMap();
        if (array_key_exists($code, $map)) {
            return $map[$code];
        } else {
            return null;
        }
    }

}
