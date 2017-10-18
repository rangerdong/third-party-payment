<?php
namespace App\Services\ThirdPayments\Contracts;



use App\Models\RechargeIf;
use App\Models\RechargeOrder;

abstract class RechargeAbstract implements AsyncCallback
{
    protected $gw_pay;
    protected $gw_query;
    protected $mch_id;
    protected $mch_key;
    protected $parameters = [];

    public function __construct(RechargeIf $rechargeIf)
    {
        $this->gw_pay = $rechargeIf->gw_pay;
        $this->gw_query = $rechargeIf->qw_query;
        $this->mch_id = $rechargeIf->mc_id;
        $this->mch_key = $rechargeIf->mc_key;
    }

    //支付接口
    abstract public function pay(array $data);

    //查询接口
    abstract public function query(RechargeOrder $rechargeOrder);

    //支付签名
    abstract public function paySign():string;

    //查询签名
    abstract public function querySign():string;

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
