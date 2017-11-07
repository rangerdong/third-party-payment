<?php
return [
    // sms 短信签名
    'sign' => '易极付',
    'lifetime' => 300,
    'driver' => env('SMS_DRIVER', 'winic'),
    'sms_id'    => env("SMS_ID"),
    'sms_pwd'   => env("SMS_PWD"),
    'cache_prefix' => 'sms_'
];

