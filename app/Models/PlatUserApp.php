<?php

namespace App\Models;

use App\Models\Observer\PlatUserAppObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatUserApp extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    //
    public function platuser()
    {
        return $this->belongsTo(PlatUser::class, 'uid');
    }
}
