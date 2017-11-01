<?php
namespace App\Services\ThirdPayments\Contracts;

use App\Models\RechargeIf;
use App\Models\RechargeOrder;

abstract class RechargeBaseAbstract implements AsyncCallback
{
    protected $mch_id;
    protected $mch_key;
    protected $is_sandbox;
    protected $gw_pay;
    protected $gw_query;
    protected $gw_pay_sandbox = null;
    protected $parameters = [];
    protected $payment_map = [];
    protected $identify;
    protected $gw_refund = null;
    protected $gw_refund_query = null;

    public function __construct()
    {
        $this->is_sandbox = config('app.website.GATEWAY_SANDBOX');
        $this->payment_map = $this->initPaymentMap();
    }

    public function initMchProfile(RechargeIf $rechargeIf)
    {
        $this->mch_id = $rechargeIf->mc_id;
        $this->mch_key = $rechargeIf->mc_key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGwPay()
    {
        return $this->is_sandbox ? ($this->getGwPaySandbox() ? $this->getGwPaySandbox() : $this->gw_pay) : $this->gw_pay;
    }

    /**
     * @param mixed $gw_pay
     */
    public function setGwPay($gw_pay)
    {
        $this->gw_pay = $gw_pay;
    }

    /**
     * @return mixed
     */
    public function getGwQuery()
    {
        return $this->gw_query;
    }

    /**
     * @param mixed $gw_query
     */
    public function setGwQuery($gw_query)
    {
        $this->gw_query = $gw_query;
    }

    /**
     * @return mixed
     */
    public function getGwPaySandbox()
    {
        return $this->gw_pay_sandbox;
    }

    /**
     * @param mixed $gw_pay_sandbox
     */
    public function setGwPaySandbox($gw_pay_sandbox)
    {
        $this->gw_pay_sandbox = $gw_pay_sandbox;
    }

    /**
     * @return array
     */
    public function getPaymentMap($code)
    {
        return $this->payment_map[$code];
    }



    /**
     * @return mixed
     */
    public function getMchId()
    {
        return $this->mch_id;
    }

    /**
     * @return mixed
     */
    public function getMchKey()
    {
        return $this->mch_key;
    }




    /**
     * @param $key
     * @param $value
     *
     * @return \App\Services\ThirdPayments\Contracts\RechargeBaseAbstract
     * @internal param array $parameters
     *
     */
    public function setParameter($key, $value): RechargeBaseAbstract
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter($key)
    {
        return array_key_exists($key, $this->parameters) ? $this->parameters[$key]: null;
    }

    public function getCallbackUrl()
    {
        return route('gateway.recharge.callback', $this->getIdentify());
    }

    public function getReturnUrl()
    {
        return route('gateway.recharge.return', $this->getIdentify());
    }

    /**
     * @return mixed
     */
    abstract public function getIdentify():string;

    /**
     *  needs child to achieve
     */
    abstract public function initPaymentMap():array ;

    abstract public function query(RechargeOrder $rechargeOrder);

    //银行支付
    abstract public function bankHref(RechargeOrder $rechargeOrder);

    //扫码支付
    abstract public function scanCode(RechargeOrder $rechargeOrder);

    //支付签名
    abstract public function paySign():string;

    //查询签名
    abstract public function querySign():string;

    //异步回调处理成功后，正确信息显示
    abstract public function showSuccess():string;

    abstract public function initParameters(RechargeOrder $rechargeOrder);



}
