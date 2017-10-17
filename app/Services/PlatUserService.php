<?php
namespace App\Services;

use App\Lib\GatewayCode;
use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;

class PlatUserService
{

    public function getRechargePayment($user, $payment)
    {
        if ($user->status != 1) {
            return GatewayResponseService::fieldError(['mch_code' => GatewayCode::MCH_NO_DISABLED]);
        }
        if ($user->recharge_api != 1) {
            return GatewayResponseService::fieldError(['mch_code' => GatewayCode::MCH_NO_GATEWAY_DISABLED]);
        }
        $payment_permit = $this->getRechargePermit($user, $payment);
        return $payment_permit;
    }

    public function getRechargePermit($user, $payment)
    {
        $payment_permit = false;
        switch ($user->recharge_mode) {
            case 0:
                $payment_permit = RechargeGroupPayment::single($user->id);
                break;
            case 1:
                $payment_permit = RechargeGroupPayment::group($user->recharge_gid);
                break;
        }
        $payment_permit = $payment_permit->where('pm_id', $payment->id)->first();
        if ($payment_permit && $payment_permit->status == 1) {
            return $payment_permit;
        }
        return GatewayResponseService::fieldError(['recharge_type' => GatewayCode::PAYMENT_DISABLED]);
    }
}
