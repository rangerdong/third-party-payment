<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictInterface extends Model
{
    //
    protected $table = 'dict_interfaces';
    protected $fillable = ['name', 'identify'];

}
