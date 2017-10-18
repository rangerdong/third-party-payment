<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class RechargeOrder extends Model implements Transformable
{
    use TransformableTrait;
    //
    protected $table = 'recharge_orders';
    protected $guarded = [];

    public function platuser()
    {
        return $this->belongsTo(PlatUser::class, 'uid');
    }

    public function proxyUser()
    {
        return $this->belongsTo(PlatUser::class, 'proxy');
    }

    public function bsUser()
    {
        return $this->belongsTo(PlatUser::class, 'business');
    }

    public function upperIf()
    {
        return $this->belongsTo(RechargeIf::class, 'upper');
    }

    public function scopeBymchno($query, $merchant_no)
    {
        return $query->where('merchant_no', $merchant_no);
    }

    public function setReqIpAttribute($ip)
    {
        $this->attributes['req_ip'] = ip2long($ip);
    }

    public function getReqIpAttribute($ip)
    {
        return long2ip($ip);
    }

//    public function setOrderDataAttribute(array $data)
//    {
//        $this->attributes['order_data'] = json_encode($data);
//    }
//
//    public function setThirdNotifyAttribute(array $data)
//    {
//        $this->attributes['third_notify'] = json_encode($data);
//    }
    
}
