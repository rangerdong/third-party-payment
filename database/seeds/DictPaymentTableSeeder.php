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
        DB::table('dict_payments')->insert([
            [
                'name' => '支付宝扫码',
                'identify' => 'alipay',
                'is_bank' => 0
            ],
            [
                'name' => '微信扫码',
                'identify' => 'weixin',
                'is_bank' => 0
            ],
            [
                'name' => '网银支付',
                'identify' => 'bank',
                'is_bank' => 0
            ],
            [
                'name' => '支付宝WAP',
                'identify' => 'alipay_wap',
                'is_bank' => 0
            ],
            [
                'name' => '微信WAP',
                'identify' => 'weixin_wap',
                'is_bank' => 0
            ],
            [
                'name' => '中国工商银行',
                'identify' => 'icbc',
                'is_bank' => 1
            ],
            [
                'name' => '中国农业银行',
                'identify' => 'abc',
                'is_bank' => 1
            ],
            [
                'name' => '招商银行',
                'identify' => 'cmb',
                'is_bank' => 1
            ],
            [
                'name' => '中国银行',
                'identify' => 'boc',
                'is_bank' => 1
            ],
            [
                'name' => '中国民生银行',
                'identify' => 'cmbc',
                'is_bank' => 1
            ],
            [
                'name' => '中国建设银行',
                'identify' => 'ccb',
                'is_bank' => 1
            ],
            [
                'name' => '中信银行',
                'identify' => 'citic',
                'is_bank' => 1
            ],
            [
                'name' => '交通银行',
                'identify' => 'comm',
                'is_bank' => 1
            ],
            [
                'name' => '兴业银行',
                'identify' => 'cib',
                'is_bank' => 1
            ],
            [
                'name' => '光大银行',
                'identify' => 'ceb',
                'is_bank' => 1
            ],
            [
                'name' => '深圳发展银行',
                'identify' => 'sdb',
                'is_bank' => 1
            ],
            [
                'name' => '中国邮政',
                'identify' => 'psbc',
                'is_bank' => 1
            ],
            [
                'name' => '北京银行',
                'identify' => 'bob',
                'is_bank' => 1
            ],
            [
                'name' => '平安银行',
                'identify' => 'pab',
                'is_bank' => 1
            ],
            [
                'name' => '上海浦东发展银行',
                'identify' => 'spdb',
                'is_bank' => 1
            ],
            [
                'name' => '广东发展银行',
                'identify' => 'gdb',
                'is_bank' => 1
            ],
            [
                'name' => '宁波银行',
                'identify' => 'nbcb',
                'is_bank' => 1
            ],
            [
                'name' => '北京农村商业银行',
                'identify' => 'bjrcb',
                'is_bank' => 1
            ],
            [
                'name' => '南京银行',
                'identify' => 'njcb',
                'is_bank' => 1
            ],
            [
                'name' => '浙商银行',
                'identify' => 'czb',
                'is_bank' => 1
            ],
            [
                'name' => '上海银行',
                'identify' => 'bosh',
                'is_bank' => 1
            ],
            [
                'name' => '华夏银行',
                'identify' => 'hxb',
                'is_bank' => 1
            ],
            [
                'name' => '杭州银行',
                'identify' => 'hzb',
                'is_bank' => 1
            ],
            [
                'name' => '浙江江稠州商业银行',
                'identify' => 'czcb',
                'is_bank' => 1
            ]
        ]);
    }
}
