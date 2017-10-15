<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RechargeGateway extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'version' => ['somtimes|required', 'regex:^\d.[0-9]'],
            'mch_code' => 'required|max:32|exists:plat_users, code',
            'mch_key' => 'required|string',
            'timestamp' => 'required|timestamp',
            'order_amt' => 'required|'
        ];
    }
}
