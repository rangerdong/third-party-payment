<?php
namespace App\Lib;

class RemitThirdMap extends AbstractMap
{
    const HUICHAO     = 'ecpss';
    const YIBAO       = 'yeepay';


    public static function getMap(): array
    {
        // TODO: Implement getMap() method.
        return [
            self::HUICHAO   => '汇潮支付',
            self::YIBAO     => '易宝支付'
        ];
    }
}
