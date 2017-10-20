<?php

namespace App\Http\Controllers\Gateway;

use App\Jobs\SendRechargeCallback;
use App\Lib\GatewayCode;
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
use App\Services\ThirdPayments\Contracts\RechargePaymentFactory;
use App\Validators\RechargeGatewayValidator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $orderInfo['recharge_type'] = $data['recharge_type'];
            $third_if = $this->rechargeFactory->getInstance($recharge_if);
            return redirect($third_if->pay($orderInfo));
        } catch (ValidatorException $exception) {
            return GatewayResponseService::fieldError($exception->getMessageBag()->getMessages());
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return GatewayResponseService::codeError(GatewayCode::SYSTEM_ERROR);
        }
    }


    public function callback(Request $request,
                             RechargeOrderNotifyService $rechargeOrderNotifyService,
                             $identify)
    {
        try{
            $obj = new XDeode();
            $id = $obj->decode($identify);
            $third_if = $this->rechargeFactory->getInstance(RechargeIf::findOrFail($id));
            echo $third_if->showSuccess();
            $notify_data = $request->all();
//            $notify_info = $third_if->callback($notify_data);
            $notify_info = ['plat_no' => $notify_data['orderid']];
            if (is_array($notify_info)) {
                $order = RechargeOrder::where('plat_no', $notify_info['plat_no'])->where('order_status', 0)->first();
                if ($order) {
                    $order = $this->orderService->updateOrder($order, $notify_data, $notify_info);
                    $notify = $rechargeOrderNotifyService->createNewNotify($order);
                    SendRechargeCallback::dispatch($notify)->onQueue('recharge.callback');
                    exit();
                }
            } else {
                echo 'sign failed';
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
