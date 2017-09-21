<?php

namespace App\Admin\Controllers;

use App\Models\DictPayment;
use App\Models\RechargeIf;
use App\Models\RechargeSplitMode;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class RechargeSplitModeController extends Controller
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

            $content->header('交易处理模式 管理');
            $content->description('模式列表');

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

            $content->header(RechargeSplitMode::find($id)->name.' 修改');
            $content->description('处理模式修改');

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

            $content->header('交易处理模式 新增');
            $content->description('新增');

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
        return Admin::grid(RechargeSplitMode::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('模式名称');
            $grid->column('dictpayment.name', '通道类型');
            $grid->rate('费率')->sortable();
            $grid->column('defaultif.name', '默认接口');
            $grid->column('spareif.name', '备用接口');
            $grid->is_default('是否默认')->display(function ($is_default) {
                return $is_default ? '是' : '否';
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(RechargeSplitMode::class, function (Form $form) {

            $payments = DictPayment::where('is_bank', 0);
            $init_ifs = RechargeIf::whereHas('payments', function ($query) use ($payments) {
                $query->where('pm_id', $payments->first()->id);
            })->pluck('name', 'id');
            $form->display('id', 'ID');
            $form->select('pm_id', '通道处理类型')
                ->options(DictPayment::where('is_bank', 0)->pluck('name', 'id'))
                ->load('df_if_id', route('getifs', 'recharge'))
                ->load('pm_if_id', route('getifs', 'recharge'));
            $form->text('name', '模式名称')->rules('required|max:100');
            $form->select('df_if_id', '默认接口商')->options($init_ifs)->rules('required');
            $form->select('sp_if_id', '备用接口商')->options($init_ifs);
            $form->text('rate', '费率')->default(99.000)
                ->help('数值在99.999-0.001之间')
                ->rules(['regex:/(^([0-9]{1,2}\.)([0-9]{1,3})$)|(^[0-9]{1,2}$)/']);
            $form->radio('is_default', '是否默认')->options([
                0 => '否',
                1 => '是'
            ])->default(0);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function (Form $form) {
                //若是此通道类型的第一条添加，则自动是默认的
                if (RechargeSplitMode::where('pm_id', $form->pm_id)->count() == 0) {
                    $form->is_default = 1;
                } else {
                    if ($form->is_default) {
                        RechargeSplitMode::where('pm_id', $form->pm_id)
                            ->where('is_default', 1)
                            ->update([
                                'is_default' => 0
                            ]);
                    }
                }
            });
        });
    }
}
