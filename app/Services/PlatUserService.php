<?php
namespace App\Services;

use App\Lib\GatewayCode;
use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;

class PlatUserService
{

    public function getPayment($user, $payment)
    {
        if ($user->status != 1) {
            return GatewayResponseService::getFieldError(['mch_code' => GatewayCode::MCH_NO_DISABLED]);
        }
        if ($user->recharge_api != 1) {
            return GatewayResponseService::getFieldError(['mch_code' => GatewayCode::MCH_NO_GATEWAY_DISABLED]);
        }
        $payment_permit = $this->getRechargePermit($user, $payment);
        if ( ! $payment_permit instanceof RechargeGroupPayment) {
            return $payment_permit;
        }
        return $payment_permit;
    }

    public function getRechargePermit($user, $payment)
    {
        $payment_permit = false;
        switch ($user->recharge_mode) {
            case 0:
                $payment_permit = $this->getSingleRechargePermit($user, $payment);
                break;
            case 1:
                $payment_permit = $this->getGroupRechargePermit($user, $payment);
                break;
        }
        return $payment_permit;
    }

    protected function getSingleRechargePermit($user, $payment)
    {
        $payment = RechargeGroupPayment::single($user->id)->payment()->where('pm_id', $payment->id)->first();
        if ($payment && $payment->status == 1) {
            return $payment;
        }
        return GatewayResponseService::getFieldError(GatewayCode::PAYMENT_DISABLED);


    }

    protected function getGroupRechargePermit($user, $payment)
    {
        $payment = RechargeGroupPayment::group($user->recharge_gid)->payment()->where('pm_id', $payment->id)->first();
        if ($payment && $payment->status == 1) {
            return $payment;
        }
        return GatewayResponseService::getFieldError(GatewayCode::PAYMENT_DISABLED);
    }
}
