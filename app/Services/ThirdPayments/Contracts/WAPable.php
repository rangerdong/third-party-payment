<?php
namespace App\Services\ThirdPayments\Contracts;

use App\Models\RechargeOrder;

interface WAPable
{
    public function wapReq(RechargeOrder $rechargeOrder);
}
