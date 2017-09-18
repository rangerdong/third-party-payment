<?php

namespace App\Admin\Controllers;

use App\Models\CyInterface;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CyInterfaceController extends Controller
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

            $content->header('交易渠道管理');
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

            $content->header('交易渠道修改');
            $content->description(CyInterface::find($id)->name. " 修改");

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

            $content->header('交易渠道添加');
            $content->description('新增渠道');

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
        return Admin::grid(CyInterface::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('name', '渠道接口名称');
            $grid->identify('接口字典标识');
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
        return Admin::form(CyInterface::class, function (Form $form) {

            $form->display('id', 'ID');
            $cy_interfaces = config('dictionary.interface.cy');
            if (empty($cy_interfaces)) {
                throw  new \Exception('暂无可添加的渠道商');
            }
            $form->select('identify', '渠道标识')->options($cy_interfaces);
            $form->text('name', '渠道定义名')->rules('required|max:255');
            $form->text('mc_id', '商户id')->rules('required|max:255');
            $form->text('mc_key', '商户密钥')->rules('required|max:255');
            $form->text('gw_pay', '支付网关')->rules('required|url');
            $form->text('gw_query', '查询网关')->rules('required|url');
            $form->text('gw_refund', '退款网关');
            $form->text('gw_refund_query', '退款查询网关');
            $form->radio('status', '是否开启')->options([
                1 => '开启',
                0 => '关闭'
            ])->default(1);
            $form->textarea('ext', '额外字段(json存储)')->rows(10);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
