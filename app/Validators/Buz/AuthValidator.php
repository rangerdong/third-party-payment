<?php

namespace App\Validators\Buz;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class AuthValidator extends LaravelValidator
{

    protected $rules = [
        'register' => [
            'token' => 'required|max:32',
            'password' => 'required|max:32|min:5',
            'trade_pwd' => 'required|max:32|min:6|different:password',
            'qq' => 'required|max:20',
            'contact' => 'required|max:100',
        ]
   ];

    protected $attributes = [
        'token' => '注册token',
        'password' => '用户密码',
        'trade_pwd' => '交易密码',
        'qq' => 'qq号',
        'contact' => '联系人姓名'
    ];
}
