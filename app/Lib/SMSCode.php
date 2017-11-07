<?php
namespace App\Lib;

class SMSCode extends AbstractCode
{
    const REGISTER          =  1;  //用户注册
    const PWD_RESET         =  2;  //重置密码
    const PWD_FORGOT        =  3;  //忘记密码
    const REMIT             =  4;  //出款验证


    public static function errMsg()
    {
        // TODO: Implement errMsg() method.
        return [
            self::REGISTER       => trans('sms.register'),
            self::PWD_RESET      => trans('sms.pwd_reset'),
            self::PWD_FORGOT     => trans('sms.pwd_forgot'),
            self::REMIT          => trans('sms.remit')
        ];
    }
}
