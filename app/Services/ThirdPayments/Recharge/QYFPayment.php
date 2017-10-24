<?php
namespace App\Services\ThirdPayments\Recharge;


use App\Models\DictPayment;
use App\Models\RechargeOrder;
use App\Services\ThirdPayments\Contracts\RechargeAbstract;

class QYFPayment extends RechargeAbstract
{
    public function callback(array $data)
    {
        // TODO: Implement callback() method.
        if ($data['returncode'] != 0) {
            return false;
        }
        if ($this->veryCallbackSign($data)) {
            return ['plat_no' => $data['orderid']];
        } else {
            return false;
        }
    }

    public function veryCallbackSign(array $data)
    {
        // TODO: Implement veryCallbackSign() method.
        $signStr  = 'returncode=' . $data['returncode']  . '&';
        $signStr .= 'userid='     . $this->getMchId()    . '&';
        $signStr .= 'orderid='    . $data['orderid']     . '&';
        $signStr .= 'money='      . $data['money']       . '&';
        $signStr .= 'keyvalue='   . $this->getMchKey();

        if ($data['sign'] == md5($signStr)) {
            return true;
        } else {
            return false;
        }
    }

    public function pay(RechargeOrder $rechargeOrder):string
    {
        // TODO: Implement pay() method.
        $this->setParameter('orderid', $rechargeOrder->plat_no);
        $this->setParameter('money', $rechargeOrder->order_amt);
        $this->setParameter('hrefurl', $this->getCallbackUrl());
        $this->setParameter('url', $this->getReturnUrl());
        $this->setParameter('bankid', $this->getPaymentMap($rechargeOrder->order_data['recharge_type']));
        $this->setParameter('ext', $rechargeOrder->order_data['body']);

        $sign = $this->paySign();

        $this->setParameter('sign', $sign);
        return $this->getPayGateway() .'?'. http_build_query($this->getParameters());
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

    public function initPaymentMap()
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

    public function showSuccess(): string
    {
        // TODO: Implement showSuccess() method.
        return 'success';
    }
}
