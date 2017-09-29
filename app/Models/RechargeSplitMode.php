<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeSplitMode extends Model
{
    //
    protected $table = 'recharge_split_modes';
    protected $appends = ['full_name'];

    public function dictpayment()
    {
        return $this->belongsTo(DictPayment::class, 'pm_id');
    }

    public function scopePayment($query, $id)
    {
        return $query->where('pm_id', $id);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', 1);
    }

    public function defaultif()
    {
        return $this->belongsTo(RechargeIf::class, 'df_if_id');
    }

    public function spareif()
    {
        return $this->belongsTo(RechargeIf::class, 'sp_if_id');
    }

    public function getFullNameAttribute()
    {
        $is_default = $this->attributes['is_default'] ? "[默认]":"";
        return $is_default . $this->attributes['name'] . "({$this->attributes['rate']})";
    }
}
