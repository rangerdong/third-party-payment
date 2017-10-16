<?php
namespace App\Services\Gateway;


use App\Lib\GatewayCode;
use App\Models\DictPayment;
use App\Models\PlatUser;
use App\Repositories\Eloquent\PlatUserRepositoryEloquent;
use App\Services\GatewayResponseService;
use App\Services\PlatUserService;

class RechargeGatewayService
{
    protected $platuser_service;

    public function __construct()
    {
        $this->platuser_service = new PlatUserService();
    }

    public function isCanPay($data)
    {
        $mch_code = $data['mch_code'];
        $recharge_type = $data['recharge_type'];

        $payment = DictPayment::where('identify', $recharge_type)->first();
        if ($payment && $payment->status != 1) {
            return GatewayResponseService::getFieldError(['recharge_type' => GatewayCode::PAYMENT_DISABLED]);
        }
        $user = PlatUser::bycode($mch_code)
            ->select('id', 'role', 'status', 'upper_id', 'key', 'code', 'settle_cycle', 'recharge_api', 'recharge_mode', 'recharge_gid')
            ->first();
        $auth_res = $this->platuser_service->getPayment($user, $payment);

        if ($auth_res !== true) {
            return $auth_res;
        }

        return true;
    }
}
