<?php

namespace App\Admin\Controllers;

use App\Models\CyInterface;
use App\Models\CySplitMode;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;

class CySplitModeController extends Controller
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

            $content->header('支付处理模式管理');
            $content->description('列表');

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

            $content->header('支付处理模式修改');
            $content->description(CySplitMode::find($id)->name.'修改');

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

            $content->header('支付处理模式 新增');
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
        return Admin::grid(CySplitMode::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('定义名称');
            $grid->type('处理模式类别')->display(function ($type) {
                return config('dictionary.payment.cy.'.$type);
            });
            $grid->default('默认')->display(function ($default) {
                return $default ? '是' : '否';
            });
            $grid->column('defaultInterface.name', '默认接口');
            $grid->column('spareInterface.name', '备用接口');
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
        return Admin::form(CySplitMode::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name', '处理模式名称')->rules('required|max:100');
            $form->select('type', '模式类型')
                ->options(config('dictionary.payment.cy'));
            $form->radio('is_default', '是否默认')->options([
                0 => '否',
                1 => '是'
            ])->default(function ($form) {
                return CySplitMode::where('type', $form->model()->type)
                    ->where('is_default', 1)->exists()
                    ? 0
                    : 1;
            });
            $form->select('default', '默认渠道')
                ->options('/admin/api/interface/cy')
                ->rules('required|min:1');
            $form->select('spare', '备用渠道')
                ->options('/admin/api/interface/cy');



            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

        });
    }
}
