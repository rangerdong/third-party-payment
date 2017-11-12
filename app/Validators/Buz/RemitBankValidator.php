<?php

namespace App\Validators\Buz;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class RemitBankValidator extends LaravelValidator
{

    protected $rules = [
        'create' => [
            'classify' => 'required|in:0,1',
            'username' => 'required|max:150',
            'category' => 'required|max:20',
            'account'  => 'required|max:30|unique:plat_user_banks,account',
            'city_id'  => 'required|exists:province_cities,id',
            'branch'   => 'required|max:255',
            'number'   => 'nullable|max:255',
            'is_default' => 'required|in:0,1'
        ]
   ];

    protected $attributes = [
        'classify'   => '账户性质',
        'username'   => '开户名',
        'category'   => '开户银行',
        'city_id'    => '开户行所在省市',
        'branch'     => '开户分支行',
        'number'     => '联行号',
        'is_default' => '是否默认'
    ];
}
