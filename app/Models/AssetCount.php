<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCount extends Model
{
    //
    protected $table = 'asset_accounts';
    protected $guarded = [];
    protected $appends = ['total'];

    public function platuser()
    {
        return $this->belongsTo(PlatUser::class, 'uid');
    }

    //scopes
    public function scopeByUserId($query, $uid)
    {
        return $query->where('uid', $uid);
    }

    public function getTotalAttribute()
    {
        return bcadd($this->attributes['available'],
            bcadd(
                $this->attributes['recharge_frozen'],
                bcadd(
                    $this->attributes['withdraw_frozen'],
                    $this->attributes['other_frozen'],
                    6
                ),
                6),
            6);
    }

}
