<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatUserProfile extends Model
{
    //
    protected $table = 'plat_user_profiles';
    protected $guarded = [];

    public function platuser()
    {
        return $this->belongsTo(PlatUser::class, 'uid');
    }

    public function city()
    {
        return $this->belongsTo(ProvinceCity::class, 'city_id');
    }

    public function getFullAddrAttribute()
    {
        return $this->city->province . $this->city->city . $this->attributes['address'];
    }
}
