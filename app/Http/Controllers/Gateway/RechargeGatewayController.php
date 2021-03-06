<?php

namespace App\Http\Controllers\Gateway;

use App\Exceptions\RechargeGatewayException;
use App\Facades\RechargeLog;
use App\Jobs\SendRechargeCallback;
use App\Lib\GatewayCode;
use App\Lib\PaymentMap;
use App\Lib\XDeode;
use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;
use App\Models\RechargeIf;
use App\Models\RechargeOrder;
use App\Services\Gateway\RechargeGatewayService;
use App\Services\GatewayResponseService;
use App\Services\RechargeOrderNotifyService;
use App\Services\RechargeOrderService;
use App\Services\RechargeSplitModeService;
use App\Services\ThirdPayments\Contracts\QRCapable;
use App\Services\ThirdPayments\Contracts\RechargePaymentFactory;
use App\Validators\Gateway\RechargeGatewayValidator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;

class RechargeGatewayController extends Controller
{
    use DispatchesJobs;
    //
    protected $validator;
    protected $gatewayService;
    protected $orderService;
    protected $rechargeFactory;

    public function __construct(RechargeGatewayValidator $rechargeGatewayValidator,
                                RechargeGatewayService $rechargeGatewayService,
                                RechargeOrderService $rechargeOrderService,
                                RechargePaymentFactory $rechargePaymentFactory)
    {
        $this->validator = $rechargeGatewayValidator;
        $this->gatewayService = $rechargeGatewayService;
        $this->orderService = $rechargeOrderService;
        $this->rechargeFactory = $rechargePaymentFactory;
    }

    public function pay(Request $request, RechargeSplitModeService $rechargeSplitModeService)
    {
        try {
            $data = $request->all();
            $this->validator->with($data)->passesOrFail('pay');
            if ( ! $this->gatewayService->verifySign($data)) {
                return GatewayResponseService::fieldError(['sign' => GatewayCode::SIGN_NOT_MATCH]);
            }
            $platuser = PlatUser::bycode($data['mch_code'])
                ->select('id', 'role', 'status', 'upper_id', 'key', 'code', 'settle_cycle', 'recharge_api', 'recharge_mode', 'recharge_gid')
                ->first();
            $group_payment = $this->gatewayService->getPayment($platuser, $data['recharge_type']);
            if ( ! $group_payment instanceof RechargeGroupPayment) {
                return $group_payment;
            }
            $recharge_if = $rechargeSplitModeService->getUsableInterfaceBySplitMode($group_payment->splitmode);
            if (! $recharge_if) {
                return GatewayResponseService::codeError(GatewayCode::SYSTEM_ERROR);
            }
            $orderInfo = $this->orderService->storeOrder($request, $platuser, $group_payment, $recharge_if);
            $third_if = $this->rechargeFactory->getInstance($recharge_if);
            if (PaymentMap::isBankHref($data['recharge_type'])) {
                return redirect($third_if->bankHref($orderInfo));
            }
            if (PaymentMap::isScanCode($data['recharge_type'])) {
                if ($third_if instanceof QRCapable) {
                    return view('gateway.recharge.index', ['qrcode' => $third_if->qrCode($orderInfo)]);
                } else {
                    return redirect($third_if->scanCode($orderInfo));
                }
            }
            if (PaymentMap::isWapReq($data['recharge_type'])) {
                return response($third_if->wapReq($orderInfo));
            }
            if (PaymentMap::isSdkReq($data['recharge_type'])) {
                $third_if->sdkReq($orderInfo);
            }
        } catch (ValidatorException $exception) {
            return GatewayResponseService::fieldError($exception->getMessageBag()->getMessages());
        } catch (\Exception $exception) {
            RechargeLog::common('INFO', $exception);
            return GatewayResponseService::codeError(GatewayCode::SYSTEM_ERROR, $exception->getMessage());
        }
    }


    public function callback(Request $request,
                             RechargeOrderNotifyService $rechargeOrderNotifyService,
                             $identify)
    {
        try{
            DB::beginTransaction();
            $third_if = $this->rechargeFactory->getInstance($identify);
            echo $third_if->showSuccess();
            $notify_data = $request->all();
            $orderNo = $third_if->getOrderNoFromCallback($notify_data);
            if ($orderNo) {
                $order = RechargeOrder::where('plat_no', $orderNo)->where('order_status', 0)->first();
                if ($order) {
                    $third_if->initMchProfile($order->upperIf);
//                    $notify_info = $third_if->callback($order);
                    $notify_info = ['plat_no' => $notify_data['orderid']];
                    if ($notify_info !== false) {
                        $order = $this->orderService->updateOrder($order, $notify_data, $notify_info);
                        $this->orderService->settleOrder($order);
                        $notify = $rechargeOrderNotifyService->createNewNotify($order);
                        SendRechargeCallback::dispatch($notify)->onQueue('recharge.callback');
                        DB::commit();
                        exit();
                    } else {
                        echo 'sign failed';
                    }
                }
            } else {
                echo 'notify data has no order field';
            }
        } catch (\Exception $exception) {
            DB::rollback();
            echo $exception->getMessage();
        }
    }


    public function returnHref(Request $request, $identify)
    {

    }
}
