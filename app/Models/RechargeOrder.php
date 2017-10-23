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
    protected $appends = ['origin_third_notify'];
    protected $casts = [
        'order_data' => 'array',
        'third_notify' => 'array'
    ];

    // relationships
    public function platuser()
    {
        return $this->belongsTo(PlatUser::class, 'uid');
    }

    public function app()
    {
        return $this->belongsTo(PlatUserApp::class, 'app_id');
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

    public function notify()
    {
        return $this->hasOne(RechargeOrderNotify::class, 'order_id');
    }

    //scopes
    public function scopeBymchno($query, $merchant_no)
    {
        return $query->where('merchant_no', $merchant_no);
    }

    //attributes
    public function setReqIpAttribute($ip)
    {
        $this->attributes['req_ip'] = ip2long($ip);
    }

    public function getReqIpAttribute($ip)
    {
        return long2ip($ip);
    }

    public function getOriginThirdNotifyAttribute()
    {
        return json_encode($this->attributes['third_notify']);
    }


}
