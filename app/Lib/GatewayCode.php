<?php
namespace App\Lib;

class GatewayCode extends AbstractCode
{
    //required
    const VERSION_REQUIRED = 'VERSION_REQUIRED';
    const MCH_CODE_REQUIRED = 'MCH_CODE_REQUIRED';
    const MCH_NO_REQUIRED = 'MCH_NO_REQUIRED';
    const ORDER_TIME_REQUIRED = 'ORDER_TIME_REQUIRED';
    const ORDER_AMT_REQUIRED = 'ORDER_AMT_REQUIRED';
    const RECHARGE_TYPE_REQUIRED = 'RECHARGE_TYPE_REQUIRED';
    const APP_ID_REQUIRED = 'APP_ID_REQUIRED';
    const CALLBACK_URL_REQUIRED = 'CALLBACK_URL_REQUIRED';
    const SIGN_REQUIRED = 'SIGN_REQUIRED';

    //verify
    const INVALID_VERSION = 'INVALID_VERSION';
    const SIGN_TYPE_NOT_SUPPORT = 'SIGN_TYPE_NOT_SUPPORT';
    const MCH_CODE_NOT_EXISTS = 'MCH_CODE_NOT_EXISTS';
    const ORDER_TIME_EXPIRED = 'ORDER_TIME_EXPIRED';
    const MCH_NO_UNIQUE = 'MCH_NO_UNIQUE';
    const APP_ID_NOT_EXISTS = 'APP_ID_NOT_EXISTS';
    const ORDER_TIME_FORMAT_ERROR = 'ORDER_TIME_FORMAT_ERROR';
    const CALLBACK_URL_INVALID = 'CALLBACK_URL_INVALID';
    const RETURN_URL_INVALID = 'RETURN_URL_INVALID';
    const RECHARGE_TYPE_INVALID = 'RECHARGE_TYPE_INVALID';
    const ORDER_AMT_INVALID = 'ORDER_AMT_INVALID';
    const MCH_NO_DISABLED = 'MCH_NO_DISABLED';
    const MCH_NO_GATEWAY_DISABLED = 'MCH_NO_GATEWAY_DISABLED';
    const PAYMENT_DISABLED = 'PAYMENT_DISABLED';
    const SIGN_NOT_MATCH = 'SIGN_NOT_MATCH';

    //max
    const MCH_NO_MAX = 'MAX_NO_MAX';
    const CALLBACK_URL_MAX = 'CALLBACK_URL_MAX';
    const RETURN_RUL_MAX = 'RETURN_URL_MAX';
    const APP_ID_MAX = 'APP_ID_MAX';
    const MCH_CODE_MAX = 'MCH_CODE_MAX';
    const BODY_MAX = 'BODY_MAX';

    //error
    const SYSTEM_ERROR = 'SYSTEM_ERROR';

    public static function errMsg()
    {
        // TODO: Implement errMsg() method.
        return [
            self::VERSION_REQUIRED => self::fieldRequired('version'),
            self::MCH_CODE_REQUIRED => self::fieldRequired('mch_code'),
            self::MCH_NO_REQUIRED => self::fieldRequired('mch_no'),
            self::ORDER_TIME_REQUIRED => self::fieldRequired('order_time'),
            self::ORDER_AMT_REQUIRED => self::fieldRequired('order_amt'),
            self::RECHARGE_TYPE_REQUIRED => self::fieldRequired('recharge_type'),
            self::APP_ID_REQUIRED => self::fieldRequired('app_id'),
            self::CALLBACK_URL_REQUIRED => self::fieldRequired('callback_url'),
            self::SIGN_REQUIRED => self::fieldRequired('sign'),


            self::INVALID_VERSION => '无效版本号',
            self::SIGN_TYPE_NOT_SUPPORT => '暂不支持此加密方式',
            self::MCH_CODE_NOT_EXISTS => '商户编码不存在',
            self::MCH_NO_UNIQUE => '商户订单号已存在',
            self::ORDER_TIME_EXPIRED => '订单时间已超时，请在5分钟内提交支付',
            self::APP_ID_NOT_EXISTS => '此应用标识不存在',
            self::ORDER_TIME_FORMAT_ERROR => '订单时间格式不正确, 请遵循文档规范',
            self::CALLBACK_URL_INVALID => '异步回调地址无效，请提交正确的url地址',
            self::RETURN_URL_INVALID => '同步跳转地址无效，请提交正确的url地址',
            self::RECHARGE_TYPE_INVALID => '不支持此支付方式',
            self::ORDER_AMT_INVALID => '无效的金额格式，请检查',
            self::MCH_NO_DISABLED => '此商户号状态异常，请联系客服人员',
            self::MCH_NO_GATEWAY_DISABLED => '此商户号无此接口权限，请联系客服人员开通',
            self::PAYMENT_DISABLED => '此通道已关闭',
            self::SIGN_NOT_MATCH => '签名不正确，请详细按照文档组装参数',

            self::CALLBACK_URL_MAX => self::fieldMax('callback_url', 255),
            self::RETURN_RUL_MAX => self::fieldMax('return_url', 255),
            self::MCH_CODE_MAX => self::fieldMax('mch_code', 32),
            self::MCH_NO_MAX => self::fieldMax('mch_no', 32),
            self::BODY_MAX => self::fieldMax('body', 500),
            self::APP_ID_MAX => self::fieldMax('app_id', 32),

            self::SYSTEM_ERROR => '系统内部错误'
        ];
    }


    protected static function fieldRequired($field)
    {
        return "[$field] 不能为空";
    }

    protected static function fieldMax($field, $max)
    {
        return "[$field] 长度超限 请在 $max 字符以内";
    }

}
