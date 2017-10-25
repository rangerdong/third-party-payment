<?php
namespace App\Services\ThirdPayments\Contracts;



use App\Lib\XDeode;
use App\Models\RechargeIf;
use App\Models\RechargeOrder;

abstract class RechargeBase implements AsyncCallback
{
    protected $gw_pay;
    protected $gw_query;
    protected $mch_id;
    protected $mch_key;
    protected $parameters = [];
    protected $identify;
    protected $payment_map =[];
    protected $recharge_if;

    public function __construct(RechargeIf $rechargeIf)
    {
        $this->recharge_if = $rechargeIf;
        $this->gw_pay = $rechargeIf->gw_pay;
        $this->gw_query = $rechargeIf->qw_query;
        $this->mch_id = $rechargeIf->mc_id;
        $this->mch_key = $rechargeIf->mc_key;
        $this->identify = $rechargeIf->ifdict->identify;
        $this->initPaymentMap();
    }

    public function getCallbackUrl()
    {
        return route('gateway.recharge.callback', (new XDeode())->encode($this->recharge_if->id));
    }

    public function getReturnUrl()
    {
        return route('gateway.recharge.return');
    }

    public function getMchId()
    {
        return $this->mch_id;
    }

    public function getMchKey()
    {
        return $this->mch_key;
    }

    public function getPayGateway()
    {
        return $this->gw_pay;
    }

    public function setPayGateway($gateway)
    {
        $this->gw_pay = $gateway;
        return $this;
    }

    public function getQueryGateway()
    {
        return $this->gw_query;
    }

    public function getIdentify()
    {
        return $this->identify;
    }

    public function getPaymentMap($payment)
    {
        return $this->payment_map[$payment];
    }


    /**
     *  todo: change the $this->payment_map variable
     *
     * @return mixed
     */
    abstract function initPaymentMap();


    //查询接口
    abstract public function query(RechargeOrder $rechargeOrder);

    abstract public function initParameters(RechargeOrder $rechargeOrder);

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

    /**
     * @param string $var
     *
     * @return string
     */
    public function getParameter(string $var):string
    {
        return array_key_exists($var, $this->parameters) ? $this->parameters[$var] : '';
    }

    /**
     * @return array
     */
    public function getParameters():array
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

}
