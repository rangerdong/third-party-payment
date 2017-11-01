<?php
namespace App\Services\ThirdPayments\Contracts;


interface AsyncCallback
{
    //异步回调操作
    public function callback(array $data);

    //验证异步回调签名
    public function veryCallbackSign(array $data);

    public function getOrderNoFromCallback(array $data);
}
