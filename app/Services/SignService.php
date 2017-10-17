<?php
namespace App\Services;

use App\Models\PlatUser;

class SignService
{
    public static function signMd5($data)
    {
        $user = PlatUser::bycode($data['mch_code'])->select('key')->first();
        $signStr = '';
        $sign = $data['sign'];
        array_forget($data, 'sign');
        ksort($data);
        foreach ($data as $k => $d) {
            $signStr .= $k . '=' . $d . '&';
        }
        $signStr .= $user->key;
        if ($sign == md5($signStr)) {
            return true;
        }
        return false;
    }
}
