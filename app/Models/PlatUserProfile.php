<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatUserProfile extends Model
{
    //
    protected $table = 'plat_user_profiles';

    public function platuser()
    {
        return $this->belongsTo(PlatUser::class, 'uid');
    }
}
