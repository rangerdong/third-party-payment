<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettlementIf extends Model
{
    //
    use SoftDeletes;

    protected $table = 'settlement_ifs';
    protected $dates = ['deleted_at'];

//    public function ifdict()
//    {
//        return $this->belongsTo(DictInterface::class, 'if_id');
//    }

    public function payments()
    {
        return $this->belongsToMany(DictPayment::class, 'settlement_if_pms',
            'if_id', 'pm_id');
    }
}
