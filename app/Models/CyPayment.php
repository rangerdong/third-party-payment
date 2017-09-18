<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CyPayment extends Model
{
    use SoftDeletes;
    protected $table = 'cy_payments';
    protected $dates = ['deleted_at'];

    public function splitmode()
    {
        return $this->hasOne(CySplitMode::class, 'split_mode');
    }
}
