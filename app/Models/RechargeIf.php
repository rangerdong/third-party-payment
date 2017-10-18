<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RechargeIf extends Model
{
    //
    use SoftDeletes;

    protected $table = 'recharge_ifs';
    protected $dates = ['deleted_at'];

    public function ifdict()
    {
        return $this->belongsTo(DictInterface::class, 'if_id');
    }

    public function payments()
    {
        return $this->belongsToMany(DictPayment::class, 'recharge_if_pms',
            'if_id', 'pm_id')->withPivot(['rate']);
    }

    public function scopeNormal($query)
    {
        return $query->where('status', 1);
    }
}
