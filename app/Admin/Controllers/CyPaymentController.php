<?php

namespace App\Admin\Controllers;

use App\Models\CyPayment;

use App\Models\CySplitMode;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CyPaymentController extends Controller
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

            $content->header('支付通道管理');
            $content->description('通道列表');

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

            $content->header('支付通道修改');
            $content->description(config('dictionary.payment.cy.'.CyPayment::find($id)->identify).' 修改');

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

            $content->header('支付通道新增');
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
        return Admin::grid(CyPayment::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->identify('通道')->display(function ($identify) {
                return config('dictionary.payment.cy.'.$identify);
            });
            $grid->status('状态')->display(function ($status) {
                return $status == 1 ? '开启':'关闭';
            });
            $grid->rate('费率');
            $grid->column('splitmode.name', '处理模式');

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
        return Admin::form(CyPayment::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
