<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeOrder extends Model
{
    //
    protected $table = 'recharge_orders';

    public function platuser()
    {
        return $this->belongsTo(PlatUser::class, 'uid');
    }
    
}
