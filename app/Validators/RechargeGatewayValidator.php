<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class RechargeGatewayValidator extends LaravelValidator
{

    protected $rules = [
        'pay' => [
            'version' => ['sometimes','required', 'regex:^\d.[0-9]'],
            'mch_code' => 'required|max:32|exists:plat_users, code',
            'order_time' => 'required|date|date_format:YmdHis|after: -5 minutes',
            'order_amt' => ['required'],
            'mch_no' => 'required|unique:recharge_orders, merchant_no|max:32',
            'body' => 'nullable|max:500',
            'recharge_type' => 'required',
            'app_id' => 'required|exists:plat_user_apps, app_id|max:32',
            'callback_url' => 'required|max:250|url',
            'return_url' => 'sometimes|url',
            'sign_type' => ['sometimes','required', 'regex:[md5|rsa]'],
            'sign' => 'required'
        ]
    ];
    protected $messages = [
        'version.regex' => '非法版本号',
        'sign_type.regex' => '暂不支持此签名方式',
        'mch_code.exists' => '商户编号不存在',
        'order_time.after' => '订单时间超时，请在五分钟之内提交',
        'mch_no.exists' => '商户订单号已存在',
        'app_id.exists' => '用户应用不存在',
        'required' => ':attribute 不能为空。',
        'exists' => '',
    ];

    protected $attributes = [
        'version' => '版本号',
        'sign_type' => '签名方式',
        'mch_code' => '商户编号',
        'mch_no' => '商户订单号',
        'app_id' => '用户应用',
        'order_time' => '订单生成时间',
        'order_amt' => '订单金额',
        'sign' => '签名串',
        'callback_url' => '异步通知地址',
        'return_url' => '同步跳转地址',
        'body' => '商品描述',
        'recharge_type' => '支付方式'
    ];
}
