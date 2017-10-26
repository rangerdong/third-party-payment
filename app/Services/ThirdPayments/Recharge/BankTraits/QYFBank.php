<?php
namespace App\Services\ThirdPayments\Recharge\BankTraits;

use App\Lib\BankMap;

trait QYFBank
{
    public function getBank($bank_code)
    {
        $bankMap = [
            BankMap::GONGSHANG => '',
        ];
    }
}
