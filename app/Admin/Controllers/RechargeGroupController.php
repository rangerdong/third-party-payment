<?php

namespace App\Admin\Controllers;

use App\Lib\Code;
use App\Models\DictPayment;
use App\Models\RechargeGroup;

use App\Models\RechargeGroupPayment;
use App\Models\RechargeSplitMode;
use App\Services\ApiResponseService;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class RechargeGroupController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('交易分组管理');
            $content->description('分组列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('交易分组修改');
            $content->description(RechargeGroup::find($id)->name . ' 修改');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('交易分组 新增');
            $content->description('');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(RechargeGroup::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('分组名');
            $grid->classify('分组类别')->display(function ($classify) {
                return config('dictionary.user_roles.'.$classify);
            });
            $grid->is_default('默认')->display(function ($is_default) {
                return $is_default ? '是' : '否';
            });
            $grid->actions(function ($actions) {
                $actions->append('<a href="'.route('group.payments', $actions->getKey()).'"><i class="fa fa-sliders"></i> 通道分配</a>');
            });

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(RechargeGroup::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->select('classify', '分组类型')->options(config('dictionary.user_roles'));
            $form->text('name', '分组名称')->rules('required|max:100');
            $form->radio('is_default', '是否默认')->options([
                0 => '否',
                1 => '是'
            ]);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function (Form $form) {
                if (RechargeGroup::where('classify', $form->classify)->count() == 0) {
                    $form->is_default = 1;
                } else {
                    if ($form->is_default) {
                        RechargeGroup::where('classify', $form->classify)
                            ->where('is_default', 1)
                            ->update([
                                'is_default' => 0
                            ]);
                    }
                }
            });
        });
    }

    public function payments(Request $request, $id)
    {

        if ( ! $request->isMethod('post')) {
            Admin::js('/js/admin/group/payment.js');
            return Admin::content(function (Content $content) use ($id) {
                $content->header('['.RechargeGroup::find($id)->name . '] 通道分配');
                $content->body(function (Row $row) use ($id) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $group_payments = RechargeGroupPayment::group($id)->get();
                    $row->column(12, '<a class="btn btn-sm btn-default form-history-back" href="/admin/group/recharge"><i class="fa fa-arrow-left"></i> 返回</a>');
                    $row->column(4,
                        $form->select('payment', '通道类型')
                            ->options(DictPayment::recharge()->whereNotIn('id', $group_payments->pluck('pm_id', 'pm_id'))->get()->pluck('name', 'id')));
                    $row->column(2, ' <button class="btn btn-sm btn-default left" id="add-single" data-id="'.$id.'" data-type="single"><i class="fa fa-plus"></i>添加</button>');
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
                                    ->pluck('name', 'id')
                                )
                                ->default($group_payment->mode_id)
                                ->rules('required');
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
        $this->validate($request, [
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
