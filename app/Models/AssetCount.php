<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCount extends Model
{
    //
    protected $table = 'asset_accounts';
    protected $guarded = [];
    protected $appends = ['frozen'];

    public function platuser()
    {
        return $this->belongsTo(PlatUser::class, 'uid');
    }

    public function getFrozenAttribute()
    {
        return bcadd($this->attributes['other_frozen'], bcadd($this->attributes['recharge_frozen'], $this->attributes['settle_frozen'], 4), 4);
    }

}
