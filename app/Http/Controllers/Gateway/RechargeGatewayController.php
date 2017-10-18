<?php

namespace App\Http\Controllers\Gateway;

use App\Lib\Code;
use App\Lib\GatewayCode;
use App\Models\RechargeGroupPayment;
use App\Services\ApiResponseService;
use App\Services\Gateway\RechargeGatewayService;
use App\Services\GatewayResponseService;
use App\Services\RechargeOrderService;
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

    public function pay(Request $request)
    {
        try {
            $data = $request->all();
            $this->validator->with($data)->passesOrFail('pay');
            if ( ! $this->gatewayService->verifySign($data)) {
                return GatewayResponseService::fieldError(['sign' => GatewayCode::SIGN_NOT_MATCH]);
            }
            $group_payment = $this->gatewayService->getPayment($data);
            if ( ! $group_payment instanceof RechargeGroupPayment) {
                return $group_payment;
            }
            $this->orderService->storeOrder($request, $group_payment);
        } catch (ValidatorException $exception) {
            return GatewayResponseService::fieldError($exception->getMessageBag()->getMessages());
        } catch (\Exception $exception) {
            return GatewayResponseService::codeError(GatewayCode::SYSTEM_ERROR);
        }
    }
}
