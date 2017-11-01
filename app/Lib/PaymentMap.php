<?php
namespace App\Lib;

class PaymentMap extends AbstractMap
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

    public static function getMap():array
    {
        // TODO: Implement getMap() method.
        $map = [
            self::WX            => '微信扫码',
            self::ALI           => '支付宝扫码',
            self::BANK          => '在线银行',
            self::WX_WAP        => '微信WAP',
            self::ALI_WAP       => '支付宝WAP',
            self::BANK_WAP      => '银联在线',
            self::WX_SDK        => '微信SDK',
            self::ALI_SDK       => '支付宝SDK'
        ];
        return $map;
    }
}
