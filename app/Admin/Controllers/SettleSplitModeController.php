<?php

namespace App\Admin\Controllers;

use App\Models\DictPayment;
use App\Models\SettlementIf;
use App\Models\SettleSplitMode;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SettleSplitModeController extends Controller
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

            $content->header('结算处理模式管理');
            $content->description('');

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

            $content->header(SettleSplitMode::find($id)->name. '修改');
            $content->description('');

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

            $content->header('结算通道处理模式添加');
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
        return Admin::grid(SettleSplitMode::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('模式名称');
            $grid->column('pm_id', '通道类型')->display(function ($id) {
                return $id == 0 ? '全部银行通道' : DictPayment::find($id)->name;
            });
            $grid->column('settle_cycle', '默认结算周期');
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
        return Admin::form(SettleSplitMode::class, function (Form $form) {

            $init_ifs = SettlementIf::whereHas('payments', function ($query){
                $query->where('pm_id', DictPayment::settle()->first()->id);
            })->pluck('name', 'id');
            $form->display('id', 'ID');
            $form->select('pm_id', '通道处理类型')
                ->options(DictPayment::settle()->get()->pluck('name', 'id'))
                ->load('df_if_id', route('getifs', 'settle'))
                ->load('sp_if_id', route('getifs', 'settle'))->rules('required');
            $form->text('name', '模式名称')->rules('required|max:100');
            $form->select('df_if_id', '默认接口商')->options($init_ifs)->rules('required');
            $form->select('sp_if_id', '备用接口商')->options($init_ifs);
            $form->radio('is_default', '是否默认')->options([
                0 => '否',
                1 => '是'
            ])->default(0);
            $form->number('settle_cycle', '结算周期')->default(0)->rules('required|integer|max:1|min:0');
            $form->saving(function ($form) {
                //若是此通道类型的第一条添加，则自动是默认的
                if (SettleSplitMode::where('pm_id', $form->pm_id)->where('is_default', 1)->count() == 0) {
                    $form->is_default = 1;
                } else {
                    if ($form->is_default) {
                        SettleSplitMode::where('pm_id', $form->pm_id)
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
