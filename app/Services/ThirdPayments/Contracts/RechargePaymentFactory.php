<?php
namespace App\Services\ThirdPayments\Contracts;

use App\Exceptions\RechargeGatewayException;
use App\Lib\Code;
use App\Lib\ThirdPartyMap;
use App\Models\RechargeIf;
use App\Services\ThirdPayments\Recharge\ECPSSPayment;
use App\Services\ThirdPayments\Recharge\QYFPayment;
use App\Services\ThirdPayments\Recharge\QYFPayment1;

class RechargePaymentFactory
{
    public static function getInstance($identify)
    {
        $instance = null;
        switch ($identify) {
            case ThirdPartyMap::QIANYIFU:
                $instance = new QYFPayment(); break;
            case ThirdPartyMap::HUICHAO:
                $instance = new ECPSSPayment(); break;
            default:break;
        }
        if ($instance === null) {
            throw new RechargeGatewayException('本系统暂不支持此接口标识[' . $identify . ']', Code::RECHARGE_THIRD_LOG);
        }
        if ($identify instanceof RechargeIf) {
            $instance = $instance->initMchProfile($identify);
        }
        return $instance;
    }
}
