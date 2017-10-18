<?php
namespace App\Lib;

class MathCalculate
{
    public static function getSettleByRate($amt, $rate, $digits=6)
    {
        $amt = bcmul($amt, bcdiv($rate, 100, 5), $digits);
        return $amt;
    }

}
