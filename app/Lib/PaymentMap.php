<?php
namespace App\Lib;

class PaymentMap
{
    const WX = 'weixin';
    const ALI = 'alipay';
    const WX_WAP = 'weixin_wap';
    const ALI_WAP = 'alipay_wap';
    const BANK = 'bank';
    const BANK_WAP = 'bank_wap';
    const WX_SDK = 'weixin_sdk';
    const ALI_SDK = 'alipay_sdk';

    public static function isBankHref($recharge_type)
    {
        if ($recharge_type == self::BANK || $recharge_type == self::BANK_WAP) {
            return true;
        }
        return false;
    }

    public static function isScanCode($recharge_type)
    {
        if ($recharge_type == self::WX || $recharge_type == self::ALI) {
            return true;
        }
        return false;
    }

    public static function isWapReq($recharge_type)
    {
        if ($recharge_type == self::WX_WAP || $recharge_type == self::ALI_WAP) {
            return true;
        }
        return false;
    }

    public static function isSdkReq($recharge_type)
    {
        if ($recharge_type == self::ALI_SDK || $recharge_type == self::WX_SDK) {
            return true;
        }
        return false;
    }

}
