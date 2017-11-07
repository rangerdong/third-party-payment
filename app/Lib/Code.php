<?php
namespace App\Lib;

class Code extends AbstractCode {
	const NORMAL_ERROR					= 1;				//报错
    const NOTAUTH                       = 403;              //无权访问
	const NOTFOUND					    = 404;				//资源不存在
	const UNLOGIN						= -1;				//未登陆
	const SUCCESS						= 0;				//成功
    const SYSERROR                      = 500;              //内部错误

    const RECHARGE_THIRD_LOG            = 10001;            //充值第三方错误代码
    const RECHARGE_MCH_LOG              = 10002;            //充值商户错误代码

    const JWT_INVALID                   = 20001;            //jwt token无效
    const JWT_EXPIRED                   = 20002;            //jwt token失效
    const JWT_ERROR                     = 20003;            //jwt token生成失败
    const SMS_EMPTY                     = 20004;            //sms 短信验证码为空
    const SMS_INVALID                   = 20005;            //sms 短信验证码无效

    const LOGIN_INVALID                 = 30001;            //用户名或密码错误
    const REGISTER_USER_EXISTS          = 30002;            //账户已存在

    const FATAL_ERROR					= 40000;			//致命错误
    const SIGN_ERROR 					= 40001;			//参数sign错误
    const HTTP_REQUEST_METHOD_ERROR		= 40002;			//请求method错误
    const HTTP_REQUEST_PARAM_ERROR		= 40003;			//请求参数错误


    //login



	public static function errMsg() {
		return array(
		    self::NOTAUTH                       =>  '无权访问',
			self::NOTFOUND						=>	'资源不存在',
			self::SUCCESS						=>	'success',
			self::FATAL_ERROR					=>	'接口错误,请联系管理员',
			self::SIGN_ERROR 					=>	'签名错误',
			self::HTTP_REQUEST_METHOD_ERROR		=>	'api请求方式错误',
			self::HTTP_REQUEST_PARAM_ERROR		=>	'api请求参数错误',
            self::SYSERROR                      =>  '内部错误',

            self::JWT_INVALID                   => 'token无效',
            self::JWT_EXPIRED                   => 'token失效',
            self::JWT_ERROR                     => 'token生成失败',
            self::SMS_EMPTY                     => 'sms短信验证码为空',
            self::SMS_INVALID                   => 'sms短信验证码无效',

            self::LOGIN_INVALID                 => '用户名或密码错误',
            self::REGISTER_USER_EXISTS          => '账户已存在'
		);
	}
	

}
