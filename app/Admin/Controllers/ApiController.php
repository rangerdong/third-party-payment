<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RechargeIf;
use App\Models\RechargeIfPms;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * 通过通道类型找到对应支持的接口
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $type 接口类型 recharge支付交易 settlement 结算交易
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getIfsFromPm(Request $request, $type='recharge')
    {
        $pm_id = $request->input('q');
        if ($type == 'recharge') {
            $arr = RechargeIf::whereHas('payments', function ($q) use ($pm_id){
                  $q->where('pm_id', $pm_id);
                })->get(['id', 'name as text']);
            return $arr;
        }

    }
}
