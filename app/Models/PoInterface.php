<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoInterface extends Model
{
    //
    use SoftDeletes;
    protected $table = 'po_interfaces';
    protected $dates = ['deleted_at'];

}
