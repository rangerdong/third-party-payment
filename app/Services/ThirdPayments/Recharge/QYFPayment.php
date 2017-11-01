<?php
namespace App\Services\ThirdPayments\Recharge;


use App\Lib\PaymentMap;
use App\Lib\ThirdPartyMap;
use App\Models\RechargeOrder;
use App\Services\ThirdPayments\Contracts\RechargeBase;
use App\Services\ThirdPayments\BankTraits\QYFBankMap;
use App\Services\ThirdPayments\Contracts\RechargeBaseAbstract;

class QYFPayment extends RechargeBaseAbstract
{
    use QYFBankMap;

    protected $gw_pay = 'http://wangguan.qianyifu.com:8881/gateway/pay.asp';
    protected $gw_pay_sandbox = 'http://gateway.qianyifu.com:8881/gateway/pay_test.asp';
    protected $gw_query = 'http://wangguan.qianyifu.com/gateway/query.asp';

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

    /**
     *  needs child to achieve
     */
    public function initPaymentMap():array
    {
        // TODO: Implement setPaymentMap() method.
        return [
            PaymentMap::WX => 'weixin',
            PaymentMap::ALI => 'zhifubao',
            PaymentMap::WX_WAP => 'weixin-wap',
            PaymentMap::ALI_WAP => 'zhifubao-wap',
            PaymentMap::BANK => 'wangyin'
        ];
    }


    public function query(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement query() method.
    }

    public function bankHref(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement bankHref() method.
        $this->initParameters($rechargeOrder);

        return $this->getGwPay() . '?' . http_build_query($this->getParameters());
    }

    public function scanCode(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement scanCode() method.
        $this->initParameters($rechargeOrder);
        return $this->getGwPay() . '?' . http_build_query($this->getParameters());
    }

    public function paySign(): string
    {
        /// TODO: Implement paySign() method.
        $signStr  = "userid="  . $this->getParameter('userid')  . '&';
        $signStr .= "orderid=" . $this->getParameter('orderid') . '&';
        $signStr .= "bankid="  . $this->getParameter('bankid')  . '&';
        $signStr .= "keyvalue=". $this->getMchKey();
        return md5($signStr);
    }

    public function querySign(): string
    {
        // TODO: Implement querySign() method.
    }

    public function showSuccess(): string
    {
        // TODO: Implement showSuccess() method.
        return 'success';
    }

    public function getOrderNoFromCallback(array $data)
    {
        // TODO: Implement getOrderNoFromCallback() method.
        return array_key_exists('orderid', $data) ? $data['orderid'] : null;
    }

    public function initParameters(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement initParameters() method.
        $mchData = $rechargeOrder->order_data;
        $this->setParameter('userid', $this->getMchId());
        $this->setParameter('orderid', $rechargeOrder->plat_no);
        $this->setParameter('money', $rechargeOrder->order_amt);
        $this->setParameter('hrefurl', $this->getCallbackUrl());
        $this->setParameter('url', $this->getReturnUrl());
        if (PaymentMap::isBankHref($mchData['recharge_type'])) {
            $bankid = $this->getBank($mchData['bank_code']);
        } else {
            $bankid = $this->getPaymentMap($mchData['recharge_type']);
        }
        $this->setParameter('bankid', $bankid);
        $this->setParameter('ext', $mchData['body']);
        $sign = $this->paySign();

        $this->setParameter('sign', $sign);
    }

    /**
     * @return mixed
     */
    public function getIdentify(): string
    {
        // TODO: Implement getIdentify() method.
        return ThirdPartyMap::QIANYIFU;
    }
}
