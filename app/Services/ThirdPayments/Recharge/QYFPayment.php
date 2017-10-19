<?php
namespace App\Services\Recharge\ThirdPayments;


use App\Models\DictPayment;
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
        $this->setParameter('orderid', $data['plat_no']);
        $this->setParameter('money', $data['order_amt']);
        $this->setParameter('hrefurl', route('gateway.recharge.callback', $this->getIdentify()));
        $this->setParameter('url', route('gateway.recharge.callback'));
        $this->setParameter('bankid', $this->getPaymentMap(DictPayment::find($data['pm_id'])->identify));
        $this->setParameter('ext', $data['']);

    }

    public function query(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement query() method.
    }

    public function paySign(): string
    {
        // TODO: Implement paySign() method.
        $signStr  = "userid="  . $this->getMchId()                  . '&';
        $signStr .= "orderid=" . $this->getParameter('orderid') . '&';
        $signStr .= "bankid="  . $this->getParameter('bankid')  . '&';
        $signStr .= "keyvalue=". $this->getMchKey();
        return md5($signStr);
    }

    public function querySign(): string
    {
        // TODO: Implement querySign() method.
    }

    function initPaymentMap($map)
    {
        // TODO: Implement initPaymentMap() method.
        $this->payment_map = [
            'weixin' => 'weixin',
            'alipay' => 'zhifubao',
            'weixin_wap' => 'weixin-wap',
            'alipay_wap' => 'zhifubao-wap',
            'bank' => 'wangyin'
        ];
    }
}
