<?php
namespace App\Services;

use App\Lib\MathCalculate;
use App\Lib\SystemNumber;
use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RechargeOrderService
{
    protected $platuserService;
    protected $splitModeService;

    public function __construct(PlatUserService $platUserService, RechargeSplitModeService $rechargeSplitModeService)
    {
        $this->platuserService = $platUserService;
        $this->splitModeService = $rechargeSplitModeService;
    }


    public function storeOrder(Request $request, RechargeGroupPayment $payment)
    {
        $platuser = $payment->platuser;
        /**
         * [
         * 'proxy' =>
         * 'business' =>
         * 'proxy_rate' =>
         * 'business_rate' =>
         * ]
         */
        $upper = $this->platuserService->getUppersRateInfo($platuser, $payment);
        $upper = $upper + ['proxy_settle' => MathCalculate::getSettleByRate($request->input('order_amt'), $upper['proxy_rate'])];
        $upper = $upper + ['business_settle' => MathCalculate::getSettleByRate($request->input('order_amt'), $upper['business_rate'])];
        $splitmode = $payment->splitmode;
        $plat_no = SystemNumber::getRechargeOrderNumber();
        $if = $this->splitModeService->getUsableInterfaceBySplitMode($splitmode);
        dd($if);



    }
}
