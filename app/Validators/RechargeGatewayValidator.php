<?php

namespace App\Validators;

use App\Lib\GatewayCode;
use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class RechargeGatewayValidator extends LaravelValidator
{

    protected $rules = [
        'pay' => [
            'version' => ['sometimes', 'regex:^\d.[0-9]'],
            'mch_code' => 'required|exists:plat_users,code',
            'order_time' => 'required|date|date_format:YmdHis|after: -30 minutes',
            'order_amt' => ['required', 'regex:[^([1-9][0-9]{0,14})?(0?\.\d{0,2})?$]'],
            'mch_no' => 'required|max:32|unique:recharge_orders,merchant_no',
            'body' => 'sometimes|max:500',
            'recharge_type' => ['required', 'exists:dict_payments,identify,is_bank,0'],
            'bank_code' => 'sometimes|exists:dict_payments,identify,is_bank,1',
            'app_id' => 'required|max:32|exists:plat_user_apps,app_id',
            'callback_url' => 'required|max:255|url',
            'return_url' => 'sometimes|max:255|url',
            'sign_type' => ['sometimes', 'regex:[^(md5|rsa)$]'],
            'sign' => 'required'
        ]
    ];
    protected $messages = [
        'version.regex' => GatewayCode::INVALID_VERSION,
        'sign_type.regex' => GatewayCode::SIGN_TYPE_NOT_SUPPORT,
        'mch_code.exists' => GatewayCode::MCH_CODE_NOT_EXISTS,
        'order_time.after' => GatewayCode::ORDER_TIME_EXPIRED,
        'order_time.date_format' => GatewayCode::ORDER_TIME_FORMAT_ERROR,
        'mch_no.unique' => GatewayCode::MCH_NO_UNIQUE,
        'app_id.exists' => GatewayCode::APP_ID_NOT_EXISTS,
        'recharge_type.exists' => GatewayCode::RECHARGE_TYPE_INVALID,
        'order_amt.regex' => GatewayCode::ORDER_AMT_INVALID,
        'required' => ':attribute_REQUIRED',
        'max'=> ':attribute_MAX',
        'callback_url.url' => GatewayCode::CALLBACK_URL_INVALID,
        'return_url.url' => GatewayCode::RETURN_URL_INVALID,
    ];

    protected $attributes = [
        'version' => 'VERSION',
        'sign_type' => 'SIGN_TYPE',
        'mch_code' => 'MCH_CODE',
        'mch_no' => 'MCH_NO',
        'app_id' => 'APP_ID',
        'order_time' => 'ORDER_TIME',
        'order_amt' => 'ORDER_AMT',
        'sign' => 'SIGN',
        'callback_url' => 'CALLBACK_URL',
        'return_url' => 'NOTIFY_URL',
        'body' => 'BODY',
        'recharge_type' => 'RECHARGE_TYPE'
    ];
}
