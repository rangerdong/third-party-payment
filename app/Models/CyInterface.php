<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CyInterface extends Model
{
    //
    use SoftDeletes;
    protected $table = 'cy_interfaces';
    protected $dates = ['deleted_at'];

}
