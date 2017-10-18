<?php
namespace App\Lib;

class SystemNumber
{
    public static function getRechargeOrderNumber()
    {
        return 'RN' . date('YmdHis'). substr(time(), -3) . random_int(0,9);
    }
}
