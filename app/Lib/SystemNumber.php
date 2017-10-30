<?php
namespace App\Lib;

class SystemNumber
{
    public static function getRechargeOrderNumber()
    {
        return 'RN' . date('YmdHis') . substr(time(), -3) . random_int(0,9);
    }

    public static function getWithdrawOrderNumber()
    {
        return 'WD' . date('YmdHis') . substr(time(), -3) . random_int(0, 9);
    }

    public static function getToPayPrefixNumber()
    {
        return 'TP' . date('YmdHis') . substr(time(), -3) . random_int(0, 9);
    }

    public static function getToPayOrderNumber($prefix, $number)
    {
        return $prefix . str_pad($number, 2, '0', STR_PAD_LEFT);
    }

    public static function getBatchNoNumber()
    {
        return date('YmdHis') . substr(time(), -3);
    }

}
