<?php
namespace App\Models\Observer;

use App\Models\RemitOrder;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;

class RemitOrderObserver
{
    /**
     * 监听model 更新事件
     *
     * @param \App\Models\RemitOrder $remitOrder
     *
     */
    public function updated(RemitOrder $remitOrder)
    {
        if (Admin::user()) {
            $remitOrder->admin_id = Admin::user()->id;
            $remitOrder->operated_at = Carbon::now()->toDateTimeString();
            $remitOrder->save();
        }
    }
}
