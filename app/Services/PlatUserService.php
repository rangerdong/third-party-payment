<?php
namespace App\Services;

use App\Lib\GatewayCode;
use App\Lib\BCMathLib;
use App\Models\DictPayment;
use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;

class PlatUserService
{
    protected $bcLib;

    public function __construct(BCMathLib $BCMathLib)
    {
        $this->bcLib = $BCMathLib;
    }

    public function getUppersWithSettle($amt, $platuser, $payment)
    {
        $upper = $this->getUppersRateInfo($platuser, $payment);
        $upper = $upper + ['proxy_settle' => $this->bcLib->getSettleByRate($amt, $upper['proxy_rate'])];
        $upper = $upper + ['business_settle' => $this->bcLib->getSettleByRate($amt, $upper['business_rate'])];

        return $upper;

    }

    public function getUppersRateInfo($platuser, RechargeGroupPayment $payment)
    {
        $upper = [
            'proxy' => 0,
            'business' => 0,
            'proxy_rate' => 0,
            'business_rate' => 0,
        ];
        $upper_user = $this->getUpper($platuser); //获取上级信息
        $payment = $payment->payment;
        if ($upper_user instanceof PlatUser) {
            if ($upper_user->role == 1) {
                $upper['proxy'] = $upper_user->id;
                $proxy_rate = $this->getRechargePaymentRate($upper_user, $payment);
                $upper['proxy_rate'] = $proxy_rate;
                $bs_upper = $this->getUpper($upper_user);
                if ($bs_upper instanceof PlatUser) {
                    $upper['business'] =  $bs_upper->id;
                    $bs_rate = $this->getRechargePaymentRate($bs_upper, $payment);
                    $upper['business_rate'] = $bs_rate;
                }
            } elseif ($upper_user->role == 2) {
                $upper['business'] = $upper_user->id;
                $bs_rate = $this->getRechargePaymentRate($upper_user, $payment);
                $upper['business_rate'] = $bs_rate;
            }
        }
        return $upper;
    }


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

    public function getRechargePayment($user, DictPayment $payment)
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

    public function getRechargePermit($user,DictPayment $payment)
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
