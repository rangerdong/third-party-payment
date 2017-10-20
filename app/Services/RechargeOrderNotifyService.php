<?php
namespace App\Services;

use App\Models\RechargeOrder;
use App\Models\RechargeOrderNotify;
use App\Repositories\Eloquent\RechargeOrderNotifyEloquent;
use Carbon\Carbon;

class RechargeOrderNotifyService
{
    protected $notifyEloquent;

    public function __construct(RechargeOrderNotifyEloquent $rechargeOrderNotifyEloquent)
    {
        $this->notifyEloquent = $rechargeOrderNotifyEloquent;
    }

    public function createNewNotify(RechargeOrder $rechargeOrder)
    {
        $platuser = $rechargeOrder->platuser;
        $orderInfo = $rechargeOrder->order_data;
        $params = [
            'err_code' => 0,
            'plat_no' => $rechargeOrder->plat_no,
            'mch_req_body' => base64_encode(json_encode($orderInfo)),
            'mch_code' => $platuser->code,
            'order_amt' => $orderInfo['order_amt'],
            'mch_no' => $orderInfo['mch_no'],
        ];
        $params += ['sign' => $this->getCallbackSign($params, $platuser->key)];
        $notify = $this->notifyEloquent->createByOrder($rechargeOrder, $params);
        return $notify;
    }

    public function getCallbackSign($notify_params, $userKey)
    {
        ksort($notify_params);
        $signStr = '';
        foreach ($notify_params as $k => $v) {
            $signStr .= $k . '=' . $v . '&';
        }
        $sign = md5($signStr.$userKey);
        return $sign;
    }

    public function getCurlRequest(RechargeOrderNotify $rechargeOrderNotify, $method='post'):array
    {
        $res = curlHttp($rechargeOrderNotify->notify_url, $rechargeOrderNotify->notify_body, $method);
        return $res;
    }



    public function curlRequest(RechargeOrderNotify $rechargeOrderNotify, $method='post'):bool
    {
        $res = $this->getCurlRequest($rechargeOrderNotify, $method);

        $rechargeOrderNotify->res_status = $res['http_code'];
        $rechargeOrderNotify->res_content = $res['body'];
        $rechargeOrderNotify->notified_at = Carbon::now()->toDateTimeString();
        $rechargeOrderNotify->notify_time += 1;
        if ($res['http_code'] == 200 && $res['body'] == 'success') {
            $rechargeOrderNotify->status = 1;
            $rechargeOrderNotify->save();
            return true;
        } else {
            $rechargeOrderNotify->save();
            return false;
        }

    }
}

