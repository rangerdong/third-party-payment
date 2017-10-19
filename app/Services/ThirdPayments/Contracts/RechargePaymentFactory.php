<?php
namespace App\Services\ThirdPayments\Contracts;

use App\Models\RechargeIf;
use App\Services\ThirdPayments\Recharge\QYFPayment;

class RechargePaymentFactory
{
    public static function getInstance(RechargeIf $rechargeIf)
    {
        $instance = null;
        switch ($rechargeIf->ifdict->identify) {
            case 'qyf':
                $instance = new QYFPayment($rechargeIf); break;
        }
        return $instance;
    }
}
