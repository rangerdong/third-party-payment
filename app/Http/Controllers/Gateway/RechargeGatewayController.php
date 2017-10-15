<?php

namespace App\Http\Controllers\Gateway;

use App\Lib\Code;
use App\Services\ApiResponseService;
use App\Validators\RechargeGatewayValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Prettus\Validator\Exceptions\ValidatorException;

class RechargeGatewayController extends Controller
{
    //
    protected $validator;

    public function __construct(RechargeGatewayValidator $rechargeGatewayValidator)
    {
        $this->validator = $rechargeGatewayValidator;
    }

    public function pay(Request $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail('pay');

        } catch (ValidatorException $exception) {
            return ApiResponseService::showError(Code::SYSERROR, $exception->getMessageBag());
        }

    }
}
