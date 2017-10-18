<?php
namespace App\Services\ThirdPayments\Contracts;

interface Refundable
{
    //退款接口
    public function refund();
    //退款查询接口
    public function refundQuery();
    //退款签名
    public function refundSign();
    //退款查询签名
    public function refundQuerySign();
    //退款回调
    public function refundCallback();
    //退款回调签名验证
    public function verifyRefundCallbackSign();
}
