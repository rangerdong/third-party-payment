<?php

namespace App\Admin\Controllers;

use App\Models\PoInterface;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PoInterfaceController extends Controller
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

            $content->header('结算渠道管理');
            $content->description('渠道列表');

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

            $content->header('结算渠道修改');
            $content->description(PoInterface::find($id)->name. ' 渠道修改');

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

            $content->header('结算渠道新增');
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
        return Admin::grid(PoInterface::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('name', '渠道接口名称');
            $grid->identify('接口字典标识');
            $grid->type('结算类型')->display(function ($type) {
                return $type == 1 ? '单笔': '批量';
            });
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
        return Admin::form(PoInterface::class, function (Form $form) {

            $form->display('id', 'ID');
            $po_interfaces = config('dictionary.interface.po');
            if (empty($po_interfaces)) {
                throw  new \Exception('暂无可添加的结算渠道商');
            }
            $form->select('identify', '渠道标识')->options($po_interfaces);
            $form->text('name', '渠道定义名')->rules('required|max:255');
            $form->text('mc_id', '商户id')->rules('required|max:255');
            $form->text('mc_key', '商户密钥')->rules('required|max:255');
            $form->text('gw_pay', '结算网关')->rules('required|url');
            $form->text('gw_query', '结算查询网关')->rules('required|url');
            $form->radio('type', '结算类型')->options([
                1 => '单笔',
                2 => '批量'
            ])->default(1);
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
