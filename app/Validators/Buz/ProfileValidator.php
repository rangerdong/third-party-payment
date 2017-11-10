<?php

namespace App\Validators\Buz;

use Illuminate\Validation\Rule;
use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ProfileValidator extends LaravelValidator
{

    protected $rules = [
        'authorize' => [
            'property' => 'required|in:0,1',
            'role' => 'required|in:0,1',
            'realname' => 'required|max:100',
            'idcard' => 'required|max:18|alpha_num',
            'scope' => 'required',
            'city_id' => 'required',
            'address' => 'required|max:255',
            'enterprise' => 'required_if:property,1|max:255|unique:plat_user_profiles,enterprise',
            'license' => 'required_if:property,1|max:255|unique:plat_user_profiles,license',
            'img_id_hand' => 'required|max:255',
            'img_id_front' => 'required|max:255|different:img_id_hand',
            'img_id_back' => 'required|max:255|different:img_id_hand|different:img_id_front',
            'img_license' => 'required_if:property,1|max:255|different:img_id_hand|different:img_id_front|different:img_id_back',
            'img_tax' => 'required_if:property,1|max:255|different:img_id_hand|different:img_id_front|different:img_id_back|different:img_license',
            'img_permit' => 'nullable|max:255|different:img_id_hand|different:img_id_front|different:img_id_back|different:img_license|different:img_tax',
         ]
   ];


    protected $attributes = [
        'property'       => '商户性质',
        'role'           => '账户类型',
        'realname'       => '真实姓名',
        'idcard'         => '身份证号码',
        'scope'          => '经营范围',
        'city_id'        => '所在城市',
        'address'        => '详细地址',
        'enterprise'     => '企业名称',
        'license'        => '营业执照号',
        'img_id_hand'    => '手持身份证照',
        'img_id_front'   => '身份证正面照',
        'img_id_back'    => '身份证背面照',
        'img_license'    => '营业执照号',
        'img_tax'        => '税务许可证',
        'img_permit'     => '文网文文件'
    ];
}
