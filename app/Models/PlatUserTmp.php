<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatUserTmp extends Model
{
    //
    protected $table = 'plat_user_tmp';
    protected $primaryKey = 'username';
    protected $keyType = 'string';
    protected $guarded = [];
}
