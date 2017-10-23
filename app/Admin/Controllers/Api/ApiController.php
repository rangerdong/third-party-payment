<?php
namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\Code;
use App\Models\DictPayment;
use App\Models\PlatUser;
use App\Models\PlatUserApp;
use App\Models\PlatUserProfile;
use App\Models\RechargeGroup;
use App\Models\RechargeGroupPayment;
use App\Models\RechargeIf;
use App\Models\RechargeIfPms;
use App\Models\RechargeOrder;
use App\Models\RechargeOrderNotify;
use App\Models\RechargeSplitMode;
use App\Models\SettleGroup;
use App\Models\SettlementIf;
use App\Models\SettlePayment;
use App\Models\SettleSplitMode;
use App\Services\ApiResponseService;
use App\Services\RechargeGroupService;
use App\Services\RechargeOrderNotifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        } else {
            if ($pm_id == 0) {
                $arr = SettlementIf::whereHas('payments', function ($q) {
                    $q->where('is_bank', 1);
                })->get(['id', 'name as text']);
            } else {
                $arr = SettlementIf::whereHas('payments', function ($q) use ($pm_id) {
                    $q->where('pm_id', $pm_id);
                })->get(['id', 'name as text']);
            }
        }
        return $arr;
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

    public function auditProfile(Request $request)
    {
        $pid = $request->input('id');
        $type = $request->input('audit', null);
        $profile = PlatUserProfile::findOrFail($pid);
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

    public function getSettleGroup(Request $request)
    {
        $classify = $request->input('q');
        $groups = SettleGroup::where('classify', $classify)->orderBy('is_default', 'desc')->get(['id', 'name as text']);
        if (count($groups) == 0) {
            $groups = [
                [
                    'id' => 0,
                    'text' => '无对应分组'
                ]
            ];
        }
        return response()->json($groups);
    }

    public function getSettleSplitMode(Request $request)
    {
        $pm_id = $request->input('q');
        $splitmodes = SettleSplitMode::where('pm_id', $pm_id)->get(['id', 'name as text']);
        return response()->json($splitmodes);
    }

    public function addAllSettlePayment(Request $request)
    {
        $payments = DictPayment::settle()->get();
        try {
            foreach ($payments as $payment) {
                SettlePayment::firstOrCreate(['dict_id' => $payment->id],
                    [
                        'support' => 0,
                        'mode_id' => SettleSplitMode::where('pm_id', $payment->id)
                            ->where('is_default', 1)
                            ->first()
                            ? SettleSplitMode::where('pm_id', $payment->id)
                            ->where('is_default', 1)
                            ->first()->id
                            : 0
                    ]
                );
            }
        } catch (\Exception $exception) {
            return ApiResponseService::showError(Code::FATAL_ERROR, $exception->getMessage());
        }
        return ApiResponseService::success(Code::SUCCESS);
    }

    public function auditApp(Request $request)
    {
        $audit = $request->input('audit');
        $id = $request->input('id');
        if ($audit == 'pass') {
            PlatUserApp::find($id)->update(['status' => 1]);
        } else {
            PlatUserApp::find($id)->update(['status' => 2, 'remark' => $request->input('reason', '应用审核未通过')]);
        }
        return ApiResponseService::success(Code::SUCCESS);
    }


    public function rechargeCallback(Request $request, RechargeOrderNotifyService $rechargeOrderNotifyService)
    {
        $order_id = $request->input('id');
        try {
            $returnData = $rechargeOrderNotifyService->curlRequest(RechargeOrderNotify::byOrderId($order_id)->first(), 'post', true);
            $content = '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">'.
                '异步通知报告：<br>
                目标地址：'.$returnData['rechargeOrderNotify']['notify_url'].'<br/>
                传输方式：post
                传递参数：<p style="word-break: break-all">'.$returnData['rechargeOrderNotify']['notify_body'].'</p>
                服务器状态码：'.$returnData['res']['http_code'].'<br>
                返回报文:<p style="word-break: break-all">'.$returnData['res']['body'].'</p></div>';
            return ApiResponseService::returnData(compact('content'));
        } catch (\Exception $exception) {
            return ApiResponseService::showError(Code::FATAL_ERROR, $exception->getMessage());
        }

    }
}
