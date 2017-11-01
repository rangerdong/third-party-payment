<?php
namespace App\Lib\Status;

use App\Lib\AbstractStatus;

class RemitStatus extends AbstractStatus
{
    /**单据状态 0待审核 1待打款 2打款中 3打款成功 4打款失败  5已撤销 6已退回 7审核拒绝
        0 => '待审核',
        1 => '待打款',
        2 => '打款中',
        3 => '打款成功',
        4 => '打款失败',
        5 => '已撤销',
        6 => '已退回',
        7 => '审核未通过'
     **/
    const PENDING_REVIEW         =  0;
    const REFUSE_REVIEW          =  7;
    const PENDING_REMIT          =  1;
    const REMITTING              =  2;
    const REMIT_SUCCESS          =  3;
    const REMIT_FAILED           =  4;
    const REMIT_CANCER           =  5;
    const REMIT_RETURN           =  6;

    public static function getMap():array
    {
        // TODO: Implement getStatusMap() method.
        $array = [
            self::PENDING_REVIEW    => '待审核',
            self::PENDING_REMIT     => '待打款',
            self::REMITTING         => '打款中',
            self::REMIT_SUCCESS     => '打款成功',
            self::REMIT_FAILED      => '打款失败',
            self::REMIT_CANCER      => '已撤销',
            self::REMIT_RETURN      => '已退回',
            self::REFUSE_REVIEW     => '审核未通过'
        ];
        return $array;
    }
}
