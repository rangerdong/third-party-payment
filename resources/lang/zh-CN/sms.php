<?php
return [
    'register' => "【".config('sms.sign')."】欢迎注册本平台，您的验证码为 %s，有效期为 ".(config('sms.lifetime') / 60) ."分钟"
];
