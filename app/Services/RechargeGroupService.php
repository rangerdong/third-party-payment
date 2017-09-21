<?php
namespace App\Services;

use App\Models\RechargeGroupPayment;
use App\Models\RechargeSplitMode;

class RechargeGroupService
{
    /**
     * @param \App\Models\RechargeGroupPayment $group_payments
     *
     * @return array
     */
    public static function getGroupPaymentsTable($group_payments): array
    {
        $headers =  ['通道编码', '通道名称', '商户费率', '处理模式', '通道状态'];
        $fields = [];
        if ($group_payments != null) {
            foreach ($group_payments as $k => $group_payment) {
                $dict_payment = $group_payment->payment;
                $fields[] = [
                    $dict_payment->identify,
                    $dict_payment->name,
                    $group_payment->rate,
                    $group_payment->splitmode->name,
                    $group_payment->status
                ];
            }
        }
        return [compact('headers'), compact('fields')];

    }

    protected static function selectSplitmode($payment_id, $mode_id)
    {


    }

    public static function addPayment($group_id, $payment_id)
    {
        $default_mode = RechargeSplitMode::payment($payment_id)
            ->default()
            ->first();
        if (RechargeGroupPayment::firstOrCreate(
            [
                'gid' => $group_id,
                'pm_id' => $payment_id
            ],
            [
                'rate' => $default_mode->rate,
                'mode_id' => $default_mode->id,
                'status' => $default_mode->dictpayment->status
            ]
        )) {
            return true;
        } else {
            return false;
        }
    }
}
