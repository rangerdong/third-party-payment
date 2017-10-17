<?php
namespace App\Services;

use App\Lib\GatewayCode;
use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;

class PlatUserService
{

    public function getUpper($platuser)
    {
        $upper = null;
        if ($platuser->role == 0) {
            $upper = $platuser->upper()->where('role', '<>', 0)->first();
        }
        if ($platuser->role == 1) {
            $upper = $this->getBsUpper($platuser);
        }
        return $upper;
    }

    protected function getProxyUpper($platuser)
    {
        $upper = $platuser->upper()->where('role', 1)->first();
        return $upper;
    }

    protected function getBsUpper($platuser)
    {
        $upper = $platuser->upper()->where('role', 2)->first();
        return $upper;
    }

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

    public function getRechargePaymentRate($user, $payment) : float
    {
        $payment = $this->getRechargePayment($user, $payment);
        if ($payment instanceof RechargeGroupPayment) {
            return $payment->rate;
        }
        return 0;
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
