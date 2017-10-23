<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Actions\RechargeCallback;
use App\Models\RechargeOrder;

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
        return Admin::grid(RechargeOrder::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('plat_no', '系统订单号');
            $grid->column('merchant_no', '商户订单号');
            $grid->column('platuser.username', '商户账户');
            $grid->column('order_amt', '订单金额');
            $grid->column('order_status', '订单状态')->display(function ($status) {
                return RechargeOrderPresenter::showStatus($status);
            })->sortable();
            $grid->column('is_settle', '是否结算')->display(function ($is_settle) {
                return $is_settle ? "已结算({$this->order_settle})" : '未结算';
            });
            $grid->column('upperIf.name', '接口厂商');
            $grid->column('proxyUser.username', '代理账户');


            $grid->disableCreation();

            $grid->created_at('充值时间');
            $grid->updated_at('更新时间');
            $grid->actions(function ($actions) {
                $row = $actions->row;
                if ($row['order_status'] == 1) {
                    $actions->append(new RechargeCallback($this->getKey()));
                }
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
        return Admin::form(RechargeOrder::class, function (Form $form) {

            $form->tab('订单基本信息', function ($form) {
                $form->display('id', 'ID');
                $form->display('plat_no', '系统订单号');
                $form->display('merchant_no', '商户订单号');
                $form->display('third_no', '第三方订单号');
                $form->select('order_status', '订单状态')
                    ->options([
                        0 => '未完成',
                        1 => '已完成'
                    ])->readOnly();
                $form->display('platuser.username', '充值账户');
                $form->display('app.name', '应用名称');
                $form->display('order_amt', '充值金额');
                $form->display('order_settle', '用户结算');
                $form->display('proxyUser.username', '代理账户');
                $form->display('proxy_settle', '代理结算');
                $form->display('bsUser.username', '商务账户');
                $form->display('business_settle', '商务结算');
                $form->display('req_ip', '充值ip');
                $form->select('is_settle', '是否结算')
                    ->options([
                        0 => '未结算',
                        1 => '已结算'
                    ])->readOnly();
                $form->display('settle_day', '结算周期');
                $form->display('created_at', '创建时间');
                $form->display('updated_at', '更新时间');
            })->tab('充值数据', function ($form) {
                $form->embeds('order_data', function ($form) {
                    $form->display('mch_code', '商户编码(mch_code)');
                    $form->display('app_id', '应用id(app_id)');
                    $form->display('mch_no', '商户订单号(mch_no)');
                    $form->display('recharge_type', '充值方式(recharge_type)');
                    $form->display('order_time', '订单生成时间(order_time)');
                    $form->display('order_amt', '订单金额(order_time)');
                    $form->display('body', '商品描述(body)');
                    $form->display('return_url', '同步跳转地址(return_url)');
                    $form->display('callback_url', '异步通知地址(callback_url)');
                    $form->display('sign', '签名串(sign)');
                });
            })->tab('通知商户数据', function ($form) {
                $form->display('notify.notify_url', '通知地址');
                $form->textarea('notify.notify_body', '通知数据body')->readOnly();
                $form->display('notify.notify_method', '通知方式')->with(function ($value) {
                    return 'post';
                });
                $form->display('notify.notify_time', '通知次数');
                $form->select('notify.status', '通知状态')->options([
                    0 => '失败',
                    1 => '成功'
                ])->readOnly();
                $form->display('notify.res_status', '响应状态码');
                $form->textarea('notify.res_content', '响应报文')->readOnly();
                $form->display('notify.notified_at', '上次通知时间');
            })->tab('第三方通知数据', function ($form) {
                $form->display('upperIf.name', '接口厂商名称');
                $form->display('upperIf.identify', '接口厂商标识');
                $form->textarea('origin_third_notify', '第三方厂商通知信息')->readOnly();
            });
            $form->disableSubmit();
            $form->disableReset();
        });
    }
}
