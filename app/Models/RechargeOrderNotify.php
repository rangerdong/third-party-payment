<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeOrderNotify extends Model
{
    //
    protected $table = 'recharge_order_notifies';

    public function order()
    {
        return $this->belongsTo(RechargeOrder::class, 'order_id');
    }
}
