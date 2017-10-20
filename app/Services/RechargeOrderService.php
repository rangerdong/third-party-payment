<?php
namespace App\Services;

use App\Lib\MathCalculate;
use App\Lib\SystemNumber;
use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;
use App\Models\RechargeIf;
use App\Models\RechargeIfPms;
use App\Models\RechargeOrder;
use App\Models\RechargeSplitMode;
use App\Repositories\Eloquent\RechargeOrderRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RechargeOrderService
{
    protected $platuserService;
    protected $splitModeService;
    protected $orderRepository;

    public function __construct(PlatUserService $platUserService,
                                RechargeSplitModeService $rechargeSplitModeService,
                                RechargeOrderRepositoryEloquent $rechargeOrderRepositoryEloquent)
    {
        $this->platuserService = $platUserService;
        $this->splitModeService = $rechargeSplitModeService;
        $this->orderRepository = $rechargeOrderRepositoryEloquent;
    }


    public function storeOrder(Request $request,
                               PlatUser $platUser,
                               RechargeGroupPayment $payment,
                               RechargeIf $rechargeIf)
    {
        /**
         * [
         * 'proxy' =>
         * 'business' =>
         * 'proxy_rate' =>
         * 'business_rate' =>
         * 'proxy_settle' =>
         * 'business_settle' =>
         * ]
         */
        $order_amt = $request->input('order_amt');
        $app_id = $platUser->apps()->where('app_id', $request->input('app_id'))->first();
        $mch_no = $request->input('mch_no');
        $upper = $this->platuserService->getUppersWithSettle($order_amt, $platUser, $payment);
        $plat_no = SystemNumber::getRechargeOrderNumber();
        $orderInfo = [
            'plat_no' => $plat_no,
            'merchant_no' => $mch_no,
            'uid' => $platUser->id,
            'app_id' => $app_id->id,
            'pm_id' => $payment->pm_id,
            'order_amt' => $order_amt,
            'order_rate' => $payment->rate,
            'order_settle' => MathCalculate::getSettleByRate($order_amt, $payment->rate),
            'order_status' => 0,
            'order_data' => $request->all(),
            'upper' => $rechargeIf->id,
            'upper_rate' => RechargeIfPms::where('pm_id', $payment->pm_id)->where('if_id', $rechargeIf->id)->first()->rate,
            'req_ip' => $request->getClientIp(),
            'settle_day' => $payment->settle_cycle,
            'is_settle' => 0,
            'settled_at' => Carbon::now()->addDay($payment->settle_cycle)->toDateTimeString()
        ];
        $orderInfo = $orderInfo + $upper;
        RechargeOrder::create($orderInfo);
        return $orderInfo;
    }


    /**
     * @param \App\Models\RechargeOrder $order
     * @param array                     $notify_data 异步通知参数
     * @param array                     $data        通知订单参数 ['plat_no' => 系统订单号, 'third_no' => 第三方订单号]
     *
     * @return \App\Models\RechargeOrder
     */
    public function updateOrder(RechargeOrder $order,
                                array $notify_data,
                                array $data)
    {
        $order->order_status = 1;
        if (array_key_exists('third_no', $data)) {
            $order->third_no = $data['third_no'];
        }
        $order->third_notify = $notify_data;
        $order->third_notify_time += 1;
        $order->save();
        return $order;
    }

}
