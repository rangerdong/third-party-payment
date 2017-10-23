<?php
namespace App\Lib;

class BCMathLib
{
    public function getSettleByRate($amt, $rate, $digits=6)
    {
        $amt = bcmul($amt, bcdiv($rate, 100, 5), $digits);
        return $amt;
    }

    public function isInvalidAmt($amt, $digits=6)
    {
        return bccomp($amt, 0, $digits) != -1 ? true : false;
    }

}
