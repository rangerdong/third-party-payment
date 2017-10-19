<?php

namespace App\Admin\Controllers;

use App\Models\RechargeORder;

use App\Presenters\Admin\RechargeOrderPresenter;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class RechargeOrderController extends Controller
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

            $content->header('充值订单管理');
            $content->description('订单列表');

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

            $content->header('订单详情');
            $content->description('商户订单号:'.RechargeOrder::find($id)->merchant_no);

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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(RechargeORder::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('plat_no', '系统订单号');
            $grid->column('merchant_no', '商户订单号');
            $grid->column('platuser.username', '商户账户');
            $grid->column('order_amt', '订单金额');
            $grid->column('order_status', '订单状态')->display(function ($status) {
                return RechargeOrderPresenter::showStatus($status);
            });
            $grid->column('is_settle', '是否结算')->display(function ($is_settle) {
                return $is_settle ? '已结算' : '未结算';
            });
            $grid->column('upperIf.name', '接口厂商');
            $grid->column('proxyUser.username', '代理账户');


            $grid->disableCreation();

            $grid->created_at('充值时间');
            $grid->updated_at('更新时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(RechargeORder::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
