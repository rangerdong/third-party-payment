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
    
}
