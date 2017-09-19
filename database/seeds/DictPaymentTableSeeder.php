<?php

use Illuminate\Database\Seeder;

class DictPaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('dict_payments')->insert(
            [
                'name' => '支付宝扫码',
                'identify' => 'alipay',
            ],
            [
                'name' => '微信扫码',
                'identify' => 'weixin'
            ],
            [
                'name' => '网银支付',
                'identify' => 'bank'
            ],
            [
                'name' => '支付宝WAP',
                'identify' => 'alipay_wap'
            ],
            [
                'name' => '微信WAP',
                'identify' => 'weixin_wap'
            ],
            [
                'name' => '中国工商银行',
                'identify' => 'icbc',
                'type' => 1
            ],
            [
                'name' => '中国农业银行',
                'identify' => 'abc',
                'type' => 1
            ],
            [
                'name' => '招商银行',
                'identify' => 'cmb',
                'type' => 1
            ],
            [
                'name' => '中国银行',
                'identify' => 'boc',
                'type' => 1
            ],
            [
                'name' => '中国民生银行',
                'identify' => 'cmbc',
                'type' => 1
            ],
            [
                'name' => '中国建设银行',
                'identify' => 'ccb',
                'type' => 1
            ],
            [
                'name' => '中信银行',
                'identify' => 'citic',
                'type' => 1
            ],
            [
                'name' => '交通银行',
                'identify' => 'comm',
                'type' => 1
            ],
            [
                'name' => '兴业银行',
                'identify' => 'cib',
                'type' => 1
            ],
            [
                'name' => '光大银行',
                'identify' => 'ceb',
                'type' => 1
            ],
            [
                'name' => '深圳发展银行',
                'identify' => 'sdb',
                'type' => 1
            ],
            [
                'name' => '中国邮政',
                'identify' => 'psbc',
                'type' => 1
            ],
            [
                'name' => '北京银行',
                'identify' => 'bob',
                'type' => 1
            ],
            [
                'name' => '平安银行',
                'identify' => 'pab',
                'type' => 1
            ],
            [
                'name' => '上海浦东发展银行',
                'identify' => 'spdb',
                'type' => 1
            ],
            [
                'name' => '广东发展银行',
                'identify' => 'gdb',
                'type' => 1
            ]
        );
    }
}
