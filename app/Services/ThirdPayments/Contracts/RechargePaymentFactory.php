<?php
namespace App\Services\ThirdPayments\Contracts;

use App\Exceptions\RechargeGatewayException;
use App\Lib\Code;
use App\Models\RechargeIf;
use App\Services\ThirdPayments\Recharge\ECPSSPayment;
use App\Services\ThirdPayments\Recharge\QYFPayment;

class RechargePaymentFactory
{
    public static function getInstance(RechargeIf $rechargeIf)
    {
        $instance = null;
        switch ($rechargeIf->identify) {
            case 'qyf':
                $instance = new QYFPayment($rechargeIf); break;
            case 'ecpss':
                $instance = new ECPSSPayment($rechargeIf); break;
            default:break;
        }
        if ($instance === null) {
            throw new RechargeGatewayException('本系统暂不支持此接口标识['.$rechargeIf->identify.']', Code::RECHARGE_THIRD_LOG);
        }
        return $instance;
    }
}
