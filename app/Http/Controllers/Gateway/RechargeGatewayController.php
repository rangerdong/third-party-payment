<?php

namespace App\Http\Controllers\Gateway;

use App\Lib\Code;
use App\Lib\GatewayCode;
use App\Models\PlatUser;
use App\Models\RechargeGroupPayment;
use App\Models\RechargeSplitMode;
use App\Services\ApiResponseService;
use App\Services\Gateway\RechargeGatewayService;
use App\Services\GatewayResponseService;
use App\Services\RechargeOrderService;
use App\Services\RechargeSplitModeService;
use App\Validators\RechargeGatewayValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Prettus\Validator\Exceptions\ValidatorException;

class RechargeGatewayController extends Controller
{
    //
    protected $validator;
    protected $gatewayService;
    protected $orderService;

    public function __construct(RechargeGatewayValidator $rechargeGatewayValidator,
                                RechargeGatewayService $rechargeGatewayService,
                                RechargeOrderService $rechargeOrderService)
    {
        $this->validator = $rechargeGatewayValidator;
        $this->gatewayService = $rechargeGatewayService;
        $this->orderService = $rechargeOrderService;
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
            if ($this->orderService->storeOrder($request, $platuser, $group_payment, $recharge_if)) {

            }
        } catch (ValidatorException $exception) {
            return GatewayResponseService::fieldError($exception->getMessageBag()->getMessages());
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return GatewayResponseService::codeError(GatewayCode::SYSTEM_ERROR);
        }
    }
}
