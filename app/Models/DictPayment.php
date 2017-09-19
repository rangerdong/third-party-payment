<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictPayment extends Model
{
    //
    protected $table = 'dict_payments';
    protected $fillable = ['name', 'identify', 'is_bank'];

}
