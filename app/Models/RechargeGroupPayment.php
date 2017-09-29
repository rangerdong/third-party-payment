<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeGroupPayment extends Model
{
    //
    protected $table = 'recharge_group_payments';
    protected $guarded = [];

    public function scopeGroup($query, $gid)
    {
        return $query->where('gid', $gid);
    }

    public function scopeSingle($query, $uid)
    {
        return $query->where('uid', $uid);
    }

    public function payment()
    {
        return $this->belongsTo(DictPayment::class, 'pm_id');
    }

    public function splitmode()
    {
        return $this->belongsTo(RechargeSplitMode::class, 'mode_id');
    }
}
