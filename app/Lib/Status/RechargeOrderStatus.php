<?php
namespace App\Lib\Status;

use App\Lib\AbstractStatus;

class RechargeOrderStatus extends AbstractStatus
{
    const PENDING       = 0;
    const SUCCESS       = 1;
    const FAILED        = 2;

    public static function getMap(): array
    {
        // TODO: Implement getMap() method.
        return [
            self::PENDING   =>  '待支付',
            self::SUCCESS   =>  '已完成',
            self::FAILED    =>  '失败'
        ];
    }
}
