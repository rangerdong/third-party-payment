<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Lib\Code;
use App\Models\DictPayment;
use App\Models\PlatUser;
use App\Models\PlatUserProfile;
use App\Models\RechargeGroup;
use App\Models\RechargeGroupPayment;
use App\Models\RechargeIf;
use App\Models\RechargeIfPms;
use App\Models\RechargeSplitMode;
use App\Services\ApiResponseService;
use App\Services\RechargeGroupService;
use Illuminate\Http\JsonResponse;
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

    public function addPayment(Request $request, $type, $group_id)
    {
        $payment_id = $request->input('payment_id');
        try {

            if ($payment_id == 0) {
                $payments = DictPayment::recharge()->get();
                foreach ($payments as $payment) {
                    RechargeGroupService::addPayment($group_id, $payment->id, $type);
                }
                return ApiResponseService::success(Code::SUCCESS);
            } else {
                if (RechargeGroupService::addPayment($group_id, $payment_id, $type)) {
                    return ApiResponseService::success(Code::SUCCESS);
                } else {
                    return ApiResponseService::showError(Code::FATAL_ERROR, '添加错误');
                }
            }
        } catch (\Exception $exception) {
            return ApiResponseService::showError(Code::FATAL_ERROR, $exception->getMessage());
        }
    }

    public function getRechargeMode(Request $request) : JsonResponse
    {
        $mode_type = $request->input('mode', null);
        $role = $request->input('role', null);
        $id = $request->input('id', null);
        if ($mode_type == 0) {
            return ApiResponseService::returnData(
                ['groups' => ['id' => 0, 'name' => '个人通道']]
            );
        } else {
            if ($id == null) {
                $groups = RechargeGroup::where('classify', $role)
                    ->orderBy('is_default', 'desc')
                    ->select('id', 'name', 'is_default')
                    ->get();
                return ApiResponseService::returnData([
                    'groups' => $groups
                ]);
            }

        }


    }

    public function auditProfile(Request $request, $id)
    {
        $profile = PlatUserProfile::findOrFail($id);
        $type = $request->input('type', null);
        if ($type != null) {
            $user = $profile->platuser;
            if ($type == 'pass') {
                $user->status = 1;
                $user->save();
            } elseif ($type == 'refuse') {
                $reason = $request->input('reason', '审核信息未通过，请检查!');
                $user->status = 2;
                $user->save();
                $profile->remark = $reason;
                $profile->save();
            }
            return ApiResponseService::success(Code::SUCCESS);
        } else {
            return ApiResponseService::showError(Code::HTTP_REQUEST_PARAM_ERROR);
        }
    }
}
