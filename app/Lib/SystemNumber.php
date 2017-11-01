<?php
namespace App\Lib;

class SystemNumber
{
    protected static function suffix()
    {
        return date('YmdHis') . substr(time(), -3) . random_int(0,9);
    }

    public static function getRechargeOrderNumber()
    {
        return 'RN' . self::suffix();
    }

    public static function getWithdrawOrderNumber()
    {
        return 'WD' . self::suffix();
    }

    public static function getToPayPrefixNumber()
    {
        return 'TP' . self::suffix();
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
