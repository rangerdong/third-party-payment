<?php
namespace App\Services;

use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;
use Illuminate\Http\Request;

class RechargeOrderService
{
    protected $platuserService;

    public function __construct()
    {
        $this->platuserService = new PlatUserService();
    }


    public function storeOrder(Request $request, RechargeGroupPayment $payment)
    {
        $platuser = $payment->platuser;
        $upper = [
            'proxy' => 0,
            'business' => 0,
            'proxy_fee' => 0,
            'business_fee' => 0
        ];
        $upper_user = $this->platuserService->getUpper($platuser); //获取上级信息
        if ($upper_user instanceof PlatUser) {
            if ($upper_user->role == 1) {
                $upper['proxy'] = $upper_user->id;
                $proxy_rate = $this->platuserService->getRechargePaymentRate($upper_user, $payment);
                $bs_upper = $this->platuserService->getUpper($upper_user);
                if ($bs_upper instanceof PlatUser) {
                    $upper['business'] =  $bs_upper->id;
                    $bs_rate = $this->platuserService->getRechargePaymentRate($bs_upper, $payment);
                }
            } elseif ($upper_user->role == 2) {
                $upper['business'] = $upper_user->id;
            }
        }
    }
}
