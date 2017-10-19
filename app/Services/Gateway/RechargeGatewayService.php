<?php
namespace App\Services\Gateway;


use App\Lib\GatewayCode;
use App\Models\DictPayment;
use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;
use App\Repositories\Eloquent\PlatUserRepositoryEloquent;
use App\Services\GatewayResponseService;
use App\Services\PlatUserService;
use App\Services\SignService;

class RechargeGatewayService
{
    protected $platuserService;

    public function __construct(PlatUserService $platUserService)
    {
        $this->platuserService = $platUserService;
    }


    public function verifySign($data)
    {
        if (array_key_exists('sign_type', $data) || $data['sign_type'] == 'md5') {
            return SignService::signMd5($data);
        }
    }

    public function getPayment(PlatUser $platUser, $recharge_type)
    {
        $payment = DictPayment::where('identify', $recharge_type)->first();
        if ($payment && $payment->status != 1) {
            return GatewayResponseService::fieldError(['recharge_type' => GatewayCode::PAYMENT_DISABLED]);
        }
        $payment = $this->platuserService->getRechargePayment($platUser, $payment);
        return $payment;
    }
}
