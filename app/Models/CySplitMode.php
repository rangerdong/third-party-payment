<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CySplitMode extends Model
{
    //
    use SoftDeletes;
    protected $table = 'cy_split_modes';
    protected $dates = ['deleted_at'];

    public function getLimitJsonAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setLimitJsonAttribute(array $value)
    {
        $this->attributes['limit_json'] = json_encode($value);
    }

    public function defaultInterface()
    {
        return $this->belongsTo(CyInterface::class, 'default');
    }

    public function spareInterface()
    {
        return $this->belongsTo(CyInterface::class, 'spare');
    }


}
