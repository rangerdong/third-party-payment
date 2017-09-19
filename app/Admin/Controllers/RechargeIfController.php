<?php

namespace App\Admin\Controllers;

use App\Models\DictInterface;
use App\Models\DictPayment;
use App\Models\RechargeIf;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class RechargeIfController extends Controller
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

            $content->header('支付交易接口 管理');
            $content->description('接口列表');

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

            $content->header('支付交易接口 修改');
            $content->description(RechargeIf::find($id)->name . ' 修改');

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

            $content->header('支付交易接口 添加');
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
        return Admin::grid(RechargeIf::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('接口名称');
            $grid->column('ifdict.name', '接口商');
            $grid->status('接口状态')->display(function ($status) {
                return $status ? '开启' : '关闭';
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
        return Admin::form(RechargeIf::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->select('if_id', '接口商')->options(DictInterface::all()->pluck('name', 'id'));
            $form->multipleSelect('payments', '支持通道')
                ->options(DictPayment::where('is_bank', 0)
                        ->get()
                        ->pluck('name', 'id'));
            $form->text('name', '接口名称')->rules('required|max:100');
            $form->radio('status', '状态')->options([
                0 => '关闭',
                1 => '开启'
            ])->default(1);
            $form->text('mc_id', '商户id')->rules('required|max:255');
            $form->text('mc_key', '商户密钥')->rules('required|max:255');
            $form->text('gw_pay', '支付网关')->rules('required|url');
            $form->text('gw_query', '查询网关')->rules('required|url');
            $form->text('gw_refund', '退款网关')->rules('nullable|url');
            $form->text('gw_refund_query', '退款查询网关')->rules('nullable|url');
            $form->textarea('ext', '额外字段(json存储)')->rows(10)->rules('nullable|json');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
