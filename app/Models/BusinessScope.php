<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessScope extends Model
{
    //
    protected $table = 'business_scope';

    public function upper()
    {
        return $this->belongsTo(self::class, 'parent');
    }





}
