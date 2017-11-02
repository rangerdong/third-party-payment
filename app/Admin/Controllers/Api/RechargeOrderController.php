<?php
namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendRechargeCallback;
use App\Lib\Code;
use App\Models\RechargeOrder;
use App\Repositories\Eloquent\RechargeOrderNotifyEloquent;
use App\Services\ApiResponseService;
use App\Services\RechargeOrderNotifyService;
use App\Services\RechargeOrderService;
use App\Services\ThirdPayments\Contracts\RechargePaymentFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechargeOrderController extends Controller
{
    protected $orderService;

    public function __construct(RechargeOrderService $rechargeOrderService)
    {
        $this->orderService = $rechargeOrderService;
    }

    public function resupplyOrder(Request $request,
                                  RechargePaymentFactory $rechargePaymentFactory,
                                  RechargeOrderNotifyService $rechargeOrderNotifyService)
    {
        $id = $request->input('id');
        $order = RechargeOrder::where('order_status', 0)->find($id);
        if ($order) {
            try {
                DB::beginTransaction();
                $recharge_if = $order->upperIf;
                $third_if = $rechargePaymentFactory->getInstance($recharge_if);
                $template = '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">
                %s </div>';
                $content = $third_if->query($order);
                if ($content === true) {
                    $order = $this->orderService->updateOrder($order, [], []);
                    $this->orderService->settleOrder($order);
                    $notify = $rechargeOrderNotifyService->createNewNotify($order);
                    SendRechargeCallback::dispatch($notify)->onQueue('recharge.callback');
                    $content = '<span class="label label-success">[补单成功]</span> <span class="label label-info">同时已发送通知给商户！</span>';
                } else {
                    $content = '<span class="label label-danger">[补单失败]</span> <br/><span class="label label-info">接口返回信息: </span><p style="word-break: break-all">' . $content . '</p>';
                }
                $content = sprintf($template,  $content);
                DB::commit();
                return ApiResponseService::returnData(compact('content'));
            } catch (\Exception $exception) {
                DB::rollback();
                return ApiResponseService::showError(Code::FATAL_ERROR, $exception);
            }
        } else {
            return ApiResponseService::showError(Code::NOTFOUND, '此订单状态不符');
        }

    }
}
