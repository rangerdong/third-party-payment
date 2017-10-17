<?php
namespace App\Lib;

class MathCalculate
{
    public static function getFeeByRate($amt, $rate, $digits=6)
    {
        $amt = bcmul($amt, bcsub(1, bcdiv($rate, 100, 5), 5), $digits);
    }

}
