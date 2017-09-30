<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProvinceCity extends Model
{
    //
    protected $table = 'province_cities';
    public $timestamps = false;
    protected $appends = ['province', 'city'];

    public function upper()
    {
        return $this->belongsTo(self::class, 'parent');
    }


    public function getProvinceAttribute()
    {
        if ($this->attributes['parent'] != 0) {
            return $this->upper->name;
        } else {
            return $this->attributes['name'];
        }
    }

    public function getCityAttribute()
    {
        return $this->attributes['name'];
    }


}
