<?php
namespace App\Http\Controllers\Gateway\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RechargeController extends Controller
{
    public function pay(Request $request)
    {
        $key = '$2y$10$EW3nds36DCPqwP.2hZufDuLp4vYtPRjUItaeGJmcmIqP26E3ekyeW';
        $data = $request->all();
        $signStr = '';
        ksort($data);
        foreach ($data as $k => $d) {
            $signStr .= $k . '=' . $d . '&';
        }
        $signStr .= $key;
        $sign = md5($signStr);
        $data['sign'] = $sign;

        $url = route('gateway.recharge.pay') . '?' . http_build_query($data);
        return redirect($url);

    }

    public function callback(Request $request)
    {
        return json_encode($request->all());
        Log::info('异步通知:' . json_encode($request->all()));
    }
}
