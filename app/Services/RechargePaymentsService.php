<?php
namespace App\Services;

use App\Lib\Code;
use App\Models\DictPayment;
use App\Models\PlatUser;
use App\Models\RechargeGroup;
use App\Models\RechargeGroupPayment;
use App\Models\RechargeSplitMode;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;

class RechargePaymentsService
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param  int                     $id  gid|uid
     * @param  string                  $type group|single
     */
    public static function allocatePayments(Request $request, $id, $type)
    {
        $method = $type.'Payments';
        return self::$method($request, $id);
    }

    protected static function singlePayments(Request $request, $id)
    {
        if ( ! $request->isMethod('post')) {
            Admin::script('initPaymentsButton();');
            return Admin::content(function (Content $content) use ($id) {
                $content->header('[个人]['.PlatUser::find($id)->username . '] 通道分配');
                $content->body(function (Row $row) use ($id) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $group_payments = RechargeGroupPayment::single($id)->get();
                    $row->column(12, '<a class="btn btn-sm btn-default form-history-back" href="'.route('platusers.index').'"><i class="fa fa-arrow-left"></i> 返回</a>');
                    $row->column(4,
                        $form->select('payment', '通道类型')
                            ->options(DictPayment::recharge()->whereNotIn('id', $group_payments->pluck('pm_id', 'pm_id'))->get()->pluck('name', 'id')));
                    $row->column(2, ' <input type="hidden" name="classify" value="single"><button class="btn btn-sm btn-default left" id="add-single" data-id="'.$id.'" data-type="single"><i class="fa fa-plus"></i>添加</button>');
                    $row->column(3, ' <button class="btn btn-sm btn-primary" id="add-full" data-id="'.$id.'" data-type="full"><i class="fa fa-plus"></i>添加全部</button>');
                    $row->column(3, ' <button class="btn btn-sm btn-success" id="save" data-id="'.$id.'" data-type="save"><i class="fa fa-database"></i>保存全部</button>');
                    if ($group_payments != null) {
                        foreach ($group_payments as $k => $group_payment) {
                            //生成分配的每个通道的表单
                            $form = new \Encore\Admin\Widgets\Form();
                            $dict_payment = $group_payment->payment;
                            $variable = 'group_pm.'.$group_payment->id .'.';

                            $form->display('identify', '通道编码')->default($dict_payment->identify);
                            $form->display('name', '通道名称')->default($dict_payment->name);
                            $form->text($variable.'rate', '商户费率')
                                ->default($group_payment->rate)->rules('required|numeric');
                            $form->select($variable.'mode_id', '处理模式')
                                ->options(RechargeSplitMode::payment($group_payment->pm_id)
                                    ->get()
                                    ->pluck('full_name', 'id')
                                )
                                ->default($group_payment->mode_id)
                                ->rules('required');
                            $form->text($variable.'settle_cycle', '结算周期')->default($group_payment->settle_cycle);
                            $form->radio($variable.'status', '通道状态')
                                ->options([
                                    0 => '关闭',
                                    1 => '开启'
                                ])->default($group_payment->status);
                            $row->column(12, $form->render());
                        }
                    }
                });
            });
        } else {
            $request->validate([
                'group_pm.*.rate' => ['required', 'numeric', 'regex:/(^([0-9]{1,2}\.)([0-9]{1,3})$)|(^[0-9]{1,2}$)/'],
                'group_pm.*.mode_id' => ['required']
            ]);
            $group_pms = $request->input('group_pm');
            try {
                foreach ($group_pms as $k => $group_pm) {
                    RechargeGroupPayment::find($k)->update($group_pm);
                }
                return count($group_pms) == 1 ? back() : ApiResponseService::success(Code::SUCCESS);
            } catch (\Exception $exception) {
                return ApiResponseService::showError(Code::FATAL_ERROR, $exception);
            }
        }

    }

    protected static function groupPayments(Request $request, $id)
    {
        if ( ! $request->isMethod('post')) {
            Admin::script('initPaymentsButton();');
            return Admin::content(function (Content $content) use ($id) {
                $content->header('['.RechargeGroup::find($id)->name . '] 通道分配');
                $content->body(function (Row $row) use ($id) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $group_payments = RechargeGroupPayment::group($id)->get();
                    $row->column(12, '<a class="btn btn-sm btn-default form-history-back" href="'.route('group.recharge.index').'"><i class="fa fa-arrow-left"></i> 返回</a>');
                    $row->column(4,
                        $form->select('payment', '通道类型')
                            ->options(DictPayment::recharge()->whereNotIn('id', $group_payments->pluck('pm_id', 'pm_id'))->get()->pluck('name', 'id')));
                    $row->column(2, ' <input type="hidden" name="classify" value="group"><button class="btn btn-sm btn-default left" id="add-single" data-id="'.$id.'" data-type="single"><i class="fa fa-plus"></i>添加</button>');
                    $row->column(3, ' <button class="btn btn-sm btn-primary" id="add-full" data-id="'.$id.'" data-type="full"><i class="fa fa-plus"></i>添加全部</button>');
                    $row->column(3, ' <button class="btn btn-sm btn-success" id="save" data-id="'.$id.'" data-type="save"><i class="fa fa-database"></i>保存全部</button>');
                    if ($group_payments != null) {
                        foreach ($group_payments as $k => $group_payment) {
                            //生成分配的每个通道的表单
                            $form = new \Encore\Admin\Widgets\Form();
                            $dict_payment = $group_payment->payment;
                            $variable = 'group_pm.'.$group_payment->id .'.';

                            $form->display('identify', '通道编码')->default($dict_payment->identify);
                            $form->display('name', '通道名称')->default($dict_payment->name);
                            $form->text($variable.'rate', '商户费率')
                                ->default($group_payment->rate)->rules('required|numeric');
                            $form->select($variable.'mode_id', '处理模式')
                                ->options(RechargeSplitMode::payment($group_payment->pm_id)
                                    ->get()
                                    ->pluck('full_name', 'id')
                                )
                                ->default($group_payment->mode_id)
                                ->rules('required');
                            $form->text($variable.'settle_cycle', '结算周期')->default($group_payment->settle_cycle);
                            $form->radio($variable.'status', '通道状态')
                                ->options([
                                    0 => '关闭',
                                    1 => '开启'
                                ])->default($group_payment->status);
                            $row->column(12, $form->render());
                        }
                    }
                });
            });
        }
        $request->validate([
            'group_pm.*.rate' => ['required', 'numeric', 'regex:/(^([0-9]{1,2}\.)([0-9]{1,3})$)|(^[0-9]{1,2}$)/'],
            'group_pm.*.mode_id' => ['required']
        ]);
        $group_pms = $request->input('group_pm');
        try {
            foreach ($group_pms as $k => $group_pm) {
                RechargeGroupPayment::find($k)->update($group_pm);
            }
            return count($group_pms) == 1 ? back() : ApiResponseService::success(Code::SUCCESS);
        } catch (\Exception $exception) {
            return ApiResponseService::showError(Code::FATAL_ERROR, $exception);
        }
    }
}
