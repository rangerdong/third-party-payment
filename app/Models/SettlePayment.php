<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettlePayment extends Model
{
    //
    protected $table = 'settlement_payments';
    protected $guarded = [];

    public function dictpayment()
    {
        return $this->belongsTo(DictPayment::class, 'dict_id');
    }

    public function splitmode()
    {
        return $this->belongsTo(SettleSplitMode::class, 'mode_id');
    }
}
