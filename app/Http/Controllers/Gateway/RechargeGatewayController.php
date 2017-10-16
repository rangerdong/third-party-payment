<?php

namespace App\Http\Controllers\Gateway;

use App\Lib\Code;
use App\Services\ApiResponseService;
use App\Services\Gateway\RechargeGatewayService;
use App\Services\GatewayResponseService;
use App\Validators\RechargeGatewayValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Prettus\Validator\Exceptions\ValidatorException;

class RechargeGatewayController extends Controller
{
    //
    protected $validator;
    protected $service;

    public function __construct(RechargeGatewayValidator $rechargeGatewayValidator,
                                RechargeGatewayService $rechargeGatewayService)
    {
        $this->validator = $rechargeGatewayValidator;
        $this->service = $rechargeGatewayService;
    }

    public function pay(Request $request)
    {
        try {
            $data = $request->all();
            $this->validator->with($data)->passesOrFail('pay');
            $this->service->isCanPay($data);

        } catch (ValidatorException $exception) {
            return GatewayResponseService::fieldError($exception->getMessageBag()->getMessages());
        }
    }
}
