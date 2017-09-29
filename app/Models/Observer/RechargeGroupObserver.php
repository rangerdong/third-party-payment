<?php
namespace App\Models\Observer;

use App\Models\DictPayment;
use App\Models\RechargeGroup;
use App\Services\RechargeGroupService;

class RechargeGroupObserver
{
    /**
     * 监听model 创建事件
     *
     * @param \App\Models\RechargeGroup $group
     */
    public function created(RechargeGroup $group)
    {
        $payments = DictPayment::recharge()->get();
        foreach ($payments as $payment) {
            RechargeGroupService::addPayment($group->id, $payment->id);
        }
    }
}

