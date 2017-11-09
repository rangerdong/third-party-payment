<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettleGroup extends Model
{
    //
    protected $table = 'settlement_groups';

    //scope
    public function scopeByDefault($query)
    {
        return $query->where('is_default', 1);
    }

    public function scopeByBuz($query)
    {
        return $query->where('classify', 0);
    }
}
