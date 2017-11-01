<?php
namespace App\Services\ThirdPayments\Recharge;

use App\Exceptions\RechargeGatewayException;
use App\Lib\Code;
use App\Lib\PaymentMap;
use App\Lib\ThirdPartyMap;
use App\Models\RechargeOrder;
use App\Services\ThirdPayments\Contracts\QRCapable;
use App\Services\ThirdPayments\Contracts\RechargeBaseAbstract;
use App\Services\ThirdPayments\Contracts\WAPable;
use App\Services\ThirdPayments\BankTraits\ECPSSBankMap;

class ECPSSPayment extends RechargeBaseAbstract implements QRCapable, WAPable
{
    use ECPSSBankMap;

    protected $bankGateway = 'https://gwapi.yemadai.com/pay/sslpayment';
    protected $qrCodeGateway = "https://gwapi.yemadai.com/pay/scanpay";
    protected $appGateway = 'https://gwapi.yemadai.com/pay/apppay';

    public function callback(array $data)
    {
        // TODO: Implement callback() method.
    }

    public function veryCallbackSign(array $data)
    {
        // TODO: Implement veryCallbackSign() method.
    }



    public function query(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement query() method.
    }

    public function paySign(): string
    {
        // TODO: Implement paySign() method.
        $signStr  = 'MerNo='  . $this->getParameter('MerNo')  . '&';
        $signStr .= 'BillNo=' . $this->getParameter('BillNo') . '&';
        $signStr .= 'Amount=' . $this->getParameter('Amount') . '&';
        $signStr .= 'OrderTime=' . $this->getParameter('OrderTime') . '&';
        $signStr .= 'AdviceURL=' . $this->getParameter('AdviceURL') . '&';
        $signStr .= $this->getMchKey();

        return strtoupper(md5($signStr));

    }

    public function querySign(): string
    {
        // TODO: Implement querySign() method.
    }

    public function showSuccess(): string
    {
        // TODO: Implement showSuccess() method.
        return "ok";
    }


    public function initParameters(RechargeOrder $rechargeOrder)
    {
        $mchData = $rechargeOrder->order_data;
        $this->setParameter('MerNo', $this->getMchId());
        $this->setParameter('BillNo', $rechargeOrder->plat_no);
        $this->setParameter('Amount', $rechargeOrder->order_amt);
        $this->setParameter('ReturnUrl', $this->getReturnUrl());
        $this->setParameter('AdviceUrl', $this->getCallbackUrl());
        $this->setParameter('OrderTime', date('YmdHis'));
        $this->setParameter('payType', $this->getPaymentMap($mchData['recharge_type']));
        $this->setParameter('Remark', $mchData['body']);
        if (PaymentMap::isBankHref($mchData['recharge_type']) && array_key_exists('bank_code', $mchData)) {
            $this->setParameter('defaultBankNumber', $mchData['bank_code']);
        }
        $this->setParameter('SignInfo', $this->paySign());
    }

    public function qrCode(RechargeOrder $rechargeOrder)
    {
        $this->setGwPay($this->qrCodeGateway);
        $this->initParameters($rechargeOrder);
        $post_xml = <<<xml
<?xml version="1.0" encoding="utf-8"?>
<ScanPayRequest>
<MerNo>{$this->getMchId()}</MerNo>
<BillNo>{$this->getParameter('BillNo')}</BillNo>
<payType>{$this->getParameter('payType')}</payType>
<Amount>{$this->getParameter('Amount')}</Amount>
<OrderTime>{$this->getParameter('OrderTime')}</OrderTime>
<AdviceUrl>{$this->getParameter('AdviceUrl')}</AdviceUrl>
<SignInfo>{$this->getParameter('SignInfo')}</SignInfo>
<products>{$this->getParameter('products')}</products>
<remark>{$this->getParameter('remark')}</remark>
</ScanPayRequest>
xml;
        $requestDomain = base64_encode($post_xml);
        $res = curlHttp($this->getGwPay(), compact('requestDomain'), 'post');
        if ($res['http_code'] == 200) {
            $res_xml = simplexml_load_string($res['body']);
            $response = json_decode(json_encode($res_xml), true);
            if ($response['respCode'] == '0000') {
                return $response['qrCode'];
            } else {
                throw new RechargeGatewayException("ecpss取码接口异常! 订单号:[{$this->getParameter('BillNo')}] \r\n 返回信息: [respCode]:".$response['respCode']."\t".$response['respMsg'], Code::RECHARGE_THIRD_LOG);
            }
        } else {
            throw new RechargeGatewayException("ecpss取码接口【curl 返回异常】 订单号:[{$this->getParameter('BillNo')}] \r\n 
            curl返回信息：【code】{$res['http_code']} 【msg】{$res['body']}", Code::RECHARGE_THIRD_LOG);
        }

    }

    public function bankHref(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement bankHref() method.
        $mchData = $rechargeOrder->order_data;
        $this->setParameter('MerNo', $this->getMchId());
        $this->setParameter('BillNo', $rechargeOrder->plat_no);
        $this->setParameter('Amount', $rechargeOrder->order_amt);
        $this->setParameter('ReturnURL', $this->getReturnUrl());
        $this->setParameter('AdviceURL', $this->getCallbackUrl());
        $this->setParameter('OrderTime', date('YmdHis'));
        $this->setParameter('payType', $this->getPaymentMap($mchData['recharge_type']));
        $this->setParameter('Remark', $mchData['body']);
        if (PaymentMap::isBankHref($mchData['recharge_type']) && array_key_exists('bank_code', $mchData)) {
            $this->setParameter('defaultBankNumber', $this->getBank($mchData['bank_code']));
        }
        $this->setParameter('SignInfo', $this->paySign());
        $this->setGwPay($this->bankGateway);
        return $this->getGwPay() . '?' . http_build_query($this->getParameters());
    }

    public function scanCode(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement scanCode() method.
    }

    public function wapReq(RechargeOrder $rechargeOrder)
    {
        // TODO: Implement wapReq() method.
        $this->initParameters($rechargeOrder);
        $this->setGwPay($this->appGateway);
        $post_xml = <<<xml
<?xml version="1.0" encoding="utf-8"?>
<ScanPayRequest>
<MerNo>{$this->getMchId()}</MerNo>
<BillNo>{$this->getParameter('BillNo')}</BillNo>
<payType>{$this->getParameter('payType')}</payType>
<Amount>{$this->getParameter('Amount')}</Amount>
<OrderTime>{$this->getParameter('OrderTime')}</OrderTime>
<AdviceUrl>{$this->getParameter('AdviceUrl')}</AdviceUrl>
<SignInfo>{$this->getParameter('SignInfo')}</SignInfo>
<products>{$rechargeOrder->order_amt}总额</products>
<remark>{$this->getParameter('Remark')}</remark>
</ScanPayRequest>
xml;
        $requestDomain = base64_encode($post_xml);
        $reqForm = <<<form
<form method="post" id="ecpss-from" action="{$this->getPayGateway()}">
<input name="requestDomain" value="{$requestDomain}" type="hidden">
</form><script>window.onload = function(){document.forms['ecpss-from'].submit()}</script>
form;
        return $reqForm;
    }

    public function getOrderNoFromCallback(array $data)
    {
        // TODO: Implement getOrderNoFromCallback() method.
        return array_key_exists('BillNo', $data) ? $data['BillNo'] : null;
    }

    /**
     * @return mixed
     */
    public function getIdentify(): string
    {
        // TODO: Implement getIdentify() method.
        return ThirdPartyMap::HUICHAO;
    }

    /**
     *  needs child to achieve
     */
    public function initPaymentMap(): array
    {
        return [
            PaymentMap::WX => 'WxScanPay',
            PaymentMap::ALI => 'AliScanPay',
            PaymentMap::BANK => 'B2CDebit',
            PaymentMap::BANK_WAP => 'noCard',
            PaymentMap::ALI_WAP => 'AliAppPay',
        ];
    }
}
