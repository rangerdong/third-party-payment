<?php

namespace App\Admin\Controllers;

use App\Models\DictPayment;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class DictPaymentController extends Controller
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

            $content->header('通道字典管理');
            $content->description('字典列表 (不要进行删除操作)');

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

            $content->header('通道字典修改');
            $content->description(DictPayment::find($id)->name.' 修改');

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

            $content->header('通道字典 添加');
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
        return Admin::grid(DictPayment::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('通道名');
            $grid->identify('通道编码');
            $grid->column('is_bank', '网银通道')->display(function ($is_bank) {
                return $is_bank ? '是' : '否';
            })->sortable();
            $grid->status('通道状态')->display(function ($status) {
                return $status ? '开启': '关闭';
            });
            $grid->order('排序ID')->sortable();
            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(DictPayment::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name', '通道名');
            $form->text('identify', '通道编码');
            $form->radio('is_bank', '银行通道')->options([
                0 => '否',
                1 => '是'
            ])->default(0);
            $form->radio('status', '是否开启')->options([
                0 => '关闭',
                1 => '开启'
            ])->default(1);
            $form->number('order', '排序ID')->help('客户端显示顺序');

            $form->display('updated_at', '修改时间');
        });
    }
}
