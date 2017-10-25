<?php
namespace App\Services\ThirdPayments\Contracts;

use App\Models\RechargeOrder;

interface SDKable
{
    public function sdkReq(RechargeOrder $rechargeOrder);
}
