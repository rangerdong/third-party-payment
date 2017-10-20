<?php

namespace App\Jobs;

use App\Models\RechargeOrder;
use App\Models\RechargeOrderNotify;
use App\Services\RechargeOrderNotifyService;
use App\Services\RechargeOrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendRechargeCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notify;
//    public $tries = 8;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\RechargeOrderNotify $rechargeOrderNotify
     *
     */
    public function __construct(RechargeOrderNotify $rechargeOrderNotify)
    {
        //
        $this->notify = $rechargeOrderNotify;
    }

    /**
     * Execute the job.
     *
     * @param \App\Services\RechargeOrderNotifyService $rechargeOrderNotifyService
     *
     * @return void
     */
    public function handle(RechargeOrderNotifyService $rechargeOrderNotifyService)
    {
        //
//        $temps = $this->attempts();
//        if ($temps >= 8) {
//            $this->delete();
//        }
//        //根据次数来选择发送延迟秒数
//        switch ($temps) {
//            case 1:
//                $delays = 5; //delay 5s
//                break;
//            case 2:
//                $delays = 10; //delay 10s
//                break;
//            case 3:
//                $delays = 30; //delay 0.5min
//                break;
//            case 4:
//                $delays = 2 * 60; //delay 2min
//                break;
//            case 5:
//                $delays = 10 * 60; //delay 10min
//                break;
//            case 6:
//                $delays = 30 * 60; //delay 0.5h
//                break;
//            case 7:
//                $delays = 60 * 60; //delay 1h
//                break;
//            default:
//                $delays = 0;
//                break;
//        }
        if ($rechargeOrderNotifyService->curlRequest($this->notify)) {
            $this->delete();
        } else {
            $this->release(5);
        }

    }
}
