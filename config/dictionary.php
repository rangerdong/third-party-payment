<?php

return [
    /*
   |--------------------------------------------------------------------------
   | Interface Dict
   |--------------------------------------------------------------------------
   | 渠道接口商字典表，所有集成到系统后的渠道商，在此添加好，然后去后台配置好渠道
   | 分为交易(支付)渠道 和 结算渠道
   |
   |
   */
    'interface' => [
        /*
        | 交易渠道
        */
        'cy' => [
            'yeepay' => '易宝',
            'alipay' => '支付宝',
            'weixin' => '微信支付'
        ],
        /*
        | 结算渠道
        */
        'po' => [
            'yeepay' => '易宝',
            'alipay' => '支付宝',
            'weixin' => '微信支付'
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Payment Dict
    |--------------------------------------------------------------------------
    | 支付通道字典表，所有集成到系统后的通道，在此添加好，然后去后台配置好通道
    | 分为交易(支付)通道 和 结算通道
    |
    |
    */
    'payment' => [
        /*
       | 交易通道
       */
        'cy' => [
            'alipay' => '支付宝',
            'weixin' => '微信',
            'bank' => '网银',
            'alipay_wap' => '支付宝wap',
            'weixin_wap' => '微信wap',
        ],
        /*
       | 结算通道
       */
        'po' => [
            'icbc'	    =>'中国工商银行',
            'abc'	    =>'中国农业银行',
            'cmb'	    =>'招商银行',
            'boc'	    =>'中国银行',
            'ccb'	    =>'中国建设银行',
            'cmbc'	    =>'中国民生银行',
            'citic'	    =>'中信银行',
            'comm'	    =>'交通银行',
            'cib'	    =>'兴业银行',
            'ceb'	    =>'光大银行',
            'sdb'	    =>'深圳发展银行',
            'psbc'	    =>'中国邮政',
            'bob'   	=>'北京银行',
            'pab'   	=>'平安银行',
            'spdb'  	=>'上海浦东发展银行',
            'gdb'   	=>'广东发展银行',
            'cbhb'  	=>'渤海银行',
            'hkbea' 	=>'东亚银行',
            'nbcb'  	=>'宁波银行',
            'bjrcb'	    =>'北京农村商业银行',
            'njcb'  	=>'南京银行',
            'czb'   	=>'浙商银行',
            'bosh'  	=>'上海银行',
            'srcb'  	=>'上海农村商业银行',
            'hxb'   	=>'华夏银行',
            'hzb'   	=>'杭州银行',
            'czcb'    =>'浙江江稠州商业银行'
        ]
    ],

    'user_classify' => [
        0 => '商户',
        1 => '代理',
        2 => '商务'
    ]
];

