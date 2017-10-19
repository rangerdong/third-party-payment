<?php

namespace App\Jobs;

use App\Models\RechargeOrder;
use App\Services\RechargeOrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendRechargeCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    /**
     * Create a new job instance.
     *
     * @param \App\Models\RechargeOrder $rechargeOrder
     */
    public function __construct(RechargeOrder $rechargeOrder)
    {
        //
        $this->order = $rechargeOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RechargeOrderService $rechargeOrderService)
    {
        //
        $rechargeOrderService->curlCallback($this->order);

    }
}
