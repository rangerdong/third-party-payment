<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeIfPms extends Model
{
    //
    protected $table = 'recharge_if_pms';

    protected $fillable = ['rate', 'if_id', 'pm_id'];

}
