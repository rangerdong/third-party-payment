<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Actions\RemitOrderAction;
use App\Admin\Extensions\Tools\RemitOrderClassify;
use App\Lib\BankMap;
use App\Models\RemitOrder;

use App\Presenters\Admin\ToPayOrderPresenter;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;

class ToPayOrderAuditController extends Controller
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
        Admin::script((new ToPayOrderPresenter())->itemScript());
        Admin::script((new RemitOrderAction())->script());
        $items = RemitOrder::topay()
            ->audit()
            ->select('id', 'plat_no', 'batch_no', 'uid', 'money',DB::raw('sum(`money`) as total_money'),
                DB::raw('count(`batch_no`) as num'), 'status', 'batch_no', 'created_at')
            ->groupBy('batch_no')
            ->paginate(15);
        return view('admin.orders.remit.index', compact('items'));
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
