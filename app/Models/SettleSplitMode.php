<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettleSplitMode extends Model
{
    //
    protected $table = 'settlement_split_modes';

    public function defaultif()
    {
        return $this->belongsTo(SettlementIf::class, 'df_if_id');
    }

    public function spareif()
    {
        return $this->belongsTo(SettlementIf::class, 'sp_if_id');
    }
}
