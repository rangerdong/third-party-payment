<?php
return [
    // sms 短信签名
    'sign' => '易汇云',
    'driver' => env('SMS_DRIVER', 'winic'),
    'sms_id'    => env("SMS_ID"),
    'sms_pwd'   => env("SMS_PWD")
];

