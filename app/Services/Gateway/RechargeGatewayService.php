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

    public function __construct()
    {
        $this->platuserService = new PlatUserService();
    }


    public function verifySign($data)
    {
        if ($data['sign_type'] == 'md5') {
            return SignService::signMd5($data);
        }
    }

    public function getPayment($data)
    {
        $mch_code = $data['mch_code'];
        $recharge_type = $data['recharge_type'];

        $payment = DictPayment::where('identify', $recharge_type)->first();
        if ($payment && $payment->status != 1) {
            return GatewayResponseService::fieldError(['recharge_type' => GatewayCode::PAYMENT_DISABLED]);
        }
        $user = PlatUser::bycode($mch_code)
            ->select('id', 'role', 'status', 'upper_id', 'key', 'code', 'settle_cycle', 'recharge_api', 'recharge_mode', 'recharge_gid')
            ->first();
        $payment = $this->platuserService->getRechargePayment($user, $payment);
        return $payment;
    }
}
