<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Actions\RemitOrderAction;
use App\Admin\Extensions\Tools\RemitIfsSelect;
use App\Admin\Extensions\Tools\RemitOrderClassify;
use App\Lib\BankMap;
use App\Lib\SystemNumber;
use App\Models\RemitOrder;

use App\Presenters\Admin\ToPayOrderPresenter;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Request;

class WithdrawOrderController extends Controller
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

            $content->header('提现订单管理');
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

            $content->header('提现单据详情');
            $content->description(RemitOrder::find($id)->plat_no);

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
        return Admin::grid(RemitOrder::class, function (Grid $grid) {

//            dd(SystemNumber::getBatchNoNumber(), SystemNumber::getToPayOrderNumber(SystemNumber::getToPayPrefixNumber(), 1));
            $grid->id('ID')->sortable();

            $grid->model()->withdraw();
            $grid->column('plat_no', '系统流水号');
            $grid->column('batch_no', '批次号')->sortable();
            $grid->column('platuser.username', '商户账户');
            $grid->column('classify', '出款类型')->display(function ($classify) {
                return $classify == 1 ? '代付' : '提现';
            });
            $grid->column('bk_username', '姓名');
            $grid->column('bk_account', '账号');
            $grid->column('bk_category', '银行')->display(function ($category) {
                return BankMap::getNameFromMap($category);
            });
            $grid->column('bk_prov', '省份');
            $grid->column('bk_city', '城市');
            $grid->column('bk_branch', '分行');
            $grid->column('money', '出款金额');
            $grid->column('fee', '收取手续费');
            $grid->column('ac_money', '实际扣款');
            $grid->column('status', '单据状态')->display(function ($status){
                return (new ToPayOrderPresenter())->batchStatus($status);
            });

            $grid->disableCreation();
            $grid->tools(function ($tools) {
                $tools->append(new RemitIfsSelect());
            });
            $grid->actions(function ($actions) {
                $row = $actions->row;
                $actions->append((new RemitOrderAction())->render($this->getKey(), $row['status']));
            });
            $grid->created_at('提现时间');
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
        return Admin::form(RemitOrder::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
