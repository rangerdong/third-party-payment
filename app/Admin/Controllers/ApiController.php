<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Lib\Code;
use App\Models\DictPayment;
use App\Models\RechargeGroupPayment;
use App\Models\RechargeIf;
use App\Models\RechargeIfPms;
use App\Models\RechargeSplitMode;
use App\Services\ApiResponseService;
use App\Services\RechargeGroupService;
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

    public function addPayment(Request $request, $group_id)
    {
        $payment_id = $request->input('payment_id');
        try {

            if ($payment_id == 0) {
                $payments = DictPayment::recharge()->get();
                foreach ($payments as $payment) {
                    RechargeGroupService::addPayment($group_id, $payment->id);
                }
                return ApiResponseService::success(Code::SUCCESS);
            } else {
                if (RechargeGroupService::addPayment($group_id, $payment_id)) {
                    return ApiResponseService::success(Code::SUCCESS);
                } else {
                    return ApiResponseService::showError(Code::FATAL_ERROR, '添加错误');
                }
            }
        } catch (\Exception $exception) {
            return ApiResponseService::showError(Code::FATAL_ERROR, $exception->getMessage());
        }
    }
}
