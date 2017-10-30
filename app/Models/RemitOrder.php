<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class RemitOrder extends Model implements Transformable
{
    use TransformableTrait;

    protected $guarded = ['id', 'deleted_at'];

    //scope
    public function scopeWithdraw($query)
    {
        return $query->where('classify', 0);
    }

    public function scopeTopay($query)
    {
        return $query->where('classify', 1);
    }

    public function scopeAudit($query)
    {
        return $query->whereIn('status', [0, 7]);
    }

    public function scopeNotAudit($query)
    {
        return $query->whereNotIn('status', [0, 7]);
    }


    //relationship
    public function platuser()
    {
        return $this->belongsTo(PlatUser::class, 'uid');
    }

    public function hasDetail()
    {
        return $this->hasOne(RemitOrderDetail::class, 'order_id');
    }

    public function manyChild()
    {
        return $this->hasMany(self::class, 'batch_no', 'batch_no');
    }


}
