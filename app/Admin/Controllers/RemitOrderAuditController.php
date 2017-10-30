<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Actions\RemitOrderAction;
use App\Admin\Extensions\Tools\RemitOrderClassify;
use App\Lib\BankMap;
use App\Models\RemitOrder;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;

class RemitOrderAuditController extends Controller
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

            $content->header('出款单据审核');
            $content->description('审核列表');

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

            $content->header('header');
            $content->description('description');

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
        $items = RemitOrder::topay()
            ->audit()
            ->select('id', 'plat_no', 'batch_no', 'uid', 'money',DB::raw('sum(`money`) as total_money'),
                DB::raw('count(`batch_no`) as num'), 'status', 'batch_no')
            ->groupBy('batch_no')
            ->paginate(1);
        return view('admin.orders.remit.index', compact('items'));
//        return Admin::grid(RemitOrder::class, function (Grid $grid) {
//
//            $grid->id('ID')->sortable();
//            $grid->model()->topay()->audit();
//
//            $grid->column('plat_no', '系统流水号');
//            $grid->column('batch_no', '批次号');
//            $grid->column('platuser.username', '商户账户');
//            $grid->column('classify', '出款类型')->display(function ($classify) {
//                return $classify == 1 ? '代付' : '提现';
//            });
//            $grid->column('bk_username', '姓名');
//            $grid->column('bk_account', '账号');
//            $grid->column('bk_category', '银行')->display(function ($category) {
//                return BankMap::getMap($category);
//            });
//            $grid->column('bk_prov', '省份');
//            $grid->column('bk_city', '城市');
//            $grid->column('bk_branch', '分行');
//            $grid->column('money', '出款金额');
//            $grid->column('fee', '收取手续费');
//            $grid->column('ac_money', '实际扣款');
//            $grid->column('status', '单据状态')->display(function ($status) {
//                return config('status.remitOrder.'.$status);
//            });
//            $grid->actions(function ($actions) use ($grid) {
//                $row = $actions->row;
//                $actions->append(new RemitOrderAction($this->getKey(), $row['status']));
//            });
//
//            $grid->disableCreation();
//            $grid->created_at('提现时间');
//            $grid->updated_at('更新时间');
//        });
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
