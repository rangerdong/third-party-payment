<?php
namespace App\Lib;

abstract class AbstractMap
{
    abstract static function getMap($bank_code = null);
}
