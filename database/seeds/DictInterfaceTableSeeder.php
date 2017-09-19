<?php

use Illuminate\Database\Seeder;

class DictInterfaceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('dict_interfaces')->insert([
            [
                'name' => '微信官方',
                'identify' => 'weixin'
            ],
            [
                'name' => '支付宝官方',
                'identify' => 'alipay'
            ],
            [
                'name' => '易宝',
                'identify' => 'yeepay'
            ],
            [
                'name' => '威富通',
                'identify' => 'swift'
            ],
            [
                'name' => '汇潮',
                'identify' => 'ecpss'
            ]
        ]);
    }
}
