<?php
namespace App\Lib;

class BankMap extends AbstractMap
{
    const GONGSHANG   =  'icbc';
    const NONGYE      =  'abc';
    const ZHAOSHANG   =  'cmb';
    const CHINABANK   =  'boc';
    const MINSHENG    =  'cmbc';
    const JIANSHE     =  'ccb';
    const ZHONGXIN    =  'citic';
    const JIAOTONG    =  'comm';
    const XINGYE      =  'cib';
    const GUANGDA     =  'ceb';
    const SHENFA      =  'sdb';
    const YOUZHEN     =  'psbc';
    const BEIJING     =  'bob';
    const PINGAN      =  'pab';
    const PUFA        =  'spdb';
    const GUANGFA     =  'gdb';
    const NINGBO      =  'nbcb';
    const BJNONGSHANG =  'bjrcb';
    const NANJING     =  'njcb';
    const ZHESHANG    =  'czb';
    const SHANGHAI    =  'bosh';
    const HUAXIA      =  'hxb';
    const HANGZHOU    =  'hzb';
    const ZJJIANGCHOU =  'czcb';

    public static function getMap($bank_code = null)
    {
        $map = [
            self::GONGSHANG    => '中国工商银行',
            self::NONGYE       => '中国农业银行',
            self::ZHAOSHANG    => '中国招商银行',
            self::CHINABANK    => '中国银行',
            self::MINSHENG     => '中国民生银行',
            self::JIANSHE      => '中国建设银行',
            self::ZHONGXIN     => '中信银行',
            self::JIAOTONG     => '交通银行',
            self::XINGYE       => '兴业银行',
            self::GUANGDA      => '光大银行',
            self::SHENFA       => '深圳发展银行',
            self::YOUZHEN      => '中国邮政',
            self::BEIJING      => '北京银行',
            self::PINGAN       => '平安银行',
            self::PUFA         => '浦发银行',
            self::GUANGFA      => '广东发展银行',
            self::NINGBO       => '宁波银行',
            self::BJNONGSHANG  => '北京农村商业银行',
            self::NANJING      => '南京银行',
            self::ZHESHANG     => '浙商银行',
            self::SHANGHAI     => '上海银行',
            self::HUAXIA       => '华夏银行',
            self::HANGZHOU     => '杭州银行',
            self::ZJJIANGCHOU  => '浙江江稠州商业银行'
        ];
        if ($bank_code !== null) {
            if (array_key_exists($bank_code, $map)) {
                return $map[$bank_code];
            } else {
                return null;
            }
        }
        return $map;
    }
}
