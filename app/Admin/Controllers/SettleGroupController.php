<?php

namespace App\Admin\Controllers;

use App\Models\SettleGroup;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SettleGroupController extends Controller
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

            $content->header('结算分组管理');
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

            $content->header(SettleGroup::find($id)->name . ' 修改');
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

            $content->header('结算分组添加');
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
        return Admin::grid(SettleGroup::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('分组名称');
            $grid->classify('分组类型')->display(function ($classify) {
                return config('dictionary.user_roles.'.$classify);
            });
            $grid->is_default('是否默认')->display(function ($is_default) {
                return $is_default ? '是' : '否';
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
        return Admin::form(SettleGroup::class, function (Form $form) {
            $digits = 4;

            $form->display('id', 'ID');
            $form->select('classify', '分组类型')->options(config('dictionary.user_roles'))->default(0);
            $form->text('name', '分组名称')->rules('required|max:100');
            $form->radio('is_default', '默认')->options([
                0 => '否',
                1 => '是'
            ]);
            $form->tab('平台提现', function ($form) use ($digits){
                $form->decimal('single_min', '平台单笔最低提款')
                    ->options(['digits' => $digits])
                    ->help('最大14位')
                    ->rules('required|max:15');
                $form->decimal('single_max', '平台单笔最大提款')
                    ->options(['digits' => $digits])
                    ->help('最大14位')
                    ->rules('required|max:15');
                $form->decimal('daily_total', '当日平台提款总额')
                    ->options(['digits' => $digits])
                    ->help('最大14位')
                    ->rules('required|max:15');
                $form->number('daily_max', '当日平台最大提款次数')
                    ->rules('required');
                $form->radio('fee_type', '平台提现手续费类型')->options([
                    0 => '固定',
                    1 => '比例'
                ])->default(0);
                $form->decimal('fee_fixed', '固定手续费')
                    ->options(['digits' => $digits])
                    ->help('最大14位')
                    ->default(1)
                    ->rules('required|max:15');
                $form->decimal('fee_rate', '手续费比例')
                    ->options(['digits' => 2])
                    ->rules('required|max:5')
                    ->help('当手续费类型为【比例】时生效');
                $form->decimal('fee_max', '最高手续费')
                    ->options(['digits' => $digits])
                    ->rules('required|max:10')
                    ->help('当手续费类型为【比例】时生效');
                $form->decimal('fee_min', '最低手续费')
                    ->options(['digits' => $digits])
                    ->rules('required|max:8')
                    ->help('当手续费类型为【比例】时生效');
            })->tab('api提现', function ($form) use ($digits) {
                $form->decimal('single_min_api', 'api单笔最低提款')
                    ->options(['digits' => $digits])
                    ->help('最大14位')
                    ->rules('required|max:15');
                $form->decimal('single_max_api', 'api单笔最大提款')
                    ->options(['digits' => $digits])
                    ->help('最大14位')
                    ->rules('required|max:15');
                $form->decimal('daily_total_api', 'api平台提款总额')
                    ->options(['digits' => $digits])
                    ->help('最大14位')
                    ->rules('required|max:15');
                $form->number('daily_api_call', '当日api最大提款次数')
                    ->rules('required');
                $form->radio('fee_type_api', '平台提现手续费类型')->options([
                    0 => '固定',
                    1 => '比例'
                ])->default(0);
                $form->decimal('fee_fixed_api', '固定手续费')
                    ->options(['digits' => $digits])
                    ->help('最大14位')
                    ->default(1)
                    ->rules('required|max:15');
                $form->decimal('fee_rate_api', '手续费比例')
                    ->options(['digits' => $digits])
                    ->rules('required|max:5')
                    ->help('当手续费类型为【比例】时生效');
                $form->decimal('fee_max_api', '最高手续费')
                    ->options(['digits' => $digits])
                    ->rules('required|max:10')
                    ->help('当手续费类型为【比例】时生效');
                $form->decimal('fee_min_api', '最低手续费')
                    ->options(['digits' => $digits])
                    ->rules('required|max:8')
                    ->help('当手续费类型为【比例】时生效');
            });

            $form->saving(function ($form) {
                //若是此通道类型的第一条添加，则自动是默认的
                if (SettleGroup::where('classify', $form->classify)->where('is_default', 1)->count() == 0) {
                    $form->is_default = 1;
                } else {
                    if ($form->is_default) {
                        SettleGroup::where('classify', $form->classify)
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
