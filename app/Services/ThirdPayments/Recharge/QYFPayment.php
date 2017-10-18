<?php
namespace App\Services\Recharge\ThirdPayments;


use App\Models\RechargeOrder;
use App\Services\ThirdPayments\Contracts\RechargeAbstract;

class QYFPayment extends RechargeAbstract
{
    public function callback(array $data)
    {
        // TODO: Implement callback() method.
    }

    public function veryCallbackSign(array $data)
    {
        // TODO: Implement veryCallbackSign() method.
    }

    public function pay(array $data)
    {
        // TODO: Implement pay() method.
    }

    public function query(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement query() method.
    }

    public function paySign(): string
    {
        // TODO: Implement paySign() method.
    }

    public function querySign(): string
    {
        // TODO: Implement querySign() method.
    }
}
