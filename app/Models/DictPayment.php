<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictPayment extends Model
{
    //
    protected $table = 'dict_payments';
    protected $fillable = ['name', 'identify', 'is_bank'];

    public function scopeRecharge($query)
    {
        return $query->where('is_bank', 0);
    }

    public function scopeSettle($query)
    {
        return $query->whereIn('is_bank', [1, 2]);
    }

}
