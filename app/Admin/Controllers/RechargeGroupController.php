<?php

namespace App\Admin\Controllers;

use App\Lib\Code;
use App\Models\DictPayment;
use App\Models\RechargeGroup;

use App\Models\RechargeGroupPayment;
use App\Models\RechargeSplitMode;
use App\Services\ApiResponseService;
use App\Services\RechargePaymentsService;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class RechargeGroupController extends Controller
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

            $content->header('交易分组管理');
            $content->description('分组列表');

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

            $content->header('交易分组修改');
            $content->description(RechargeGroup::find($id)->name . ' 修改');

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

            $content->header('交易分组 新增');
            $content->description('');

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
        return Admin::grid(RechargeGroup::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('分组名');
            $grid->classify('分组类别')->display(function ($classify) {
                return config('dictionary.user_roles.'.$classify);
            });
            $grid->is_default('默认')->display(function ($is_default) {
                return $is_default ? '是' : '否';
            });
            $grid->actions(function ($actions) {
                $actions->append('<a href="'.route('group.payments', $actions->getKey()).'"><i class="fa fa-sliders"></i> 通道分配</a>');
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
        return Admin::form(RechargeGroup::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->select('classify', '分组类型')->options(config('dictionary.user_roles'));
            $form->text('name', '分组名称')->rules('required|max:100');
            $form->radio('is_default', '是否默认')->options([
                0 => '否',
                1 => '是'
            ]);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function (Form $form) {
                if (RechargeGroup::where('classify', $form->classify)->count() == 0) {
                    $form->is_default = 1;
                } else {
                    if ($form->is_default) {
                        RechargeGroup::where('classify', $form->classify)
                            ->where('is_default', 1)
                            ->update([
                                'is_default' => 0
                            ]);
                    }
                }
            });
        });
    }

    public function payments(Request $request, $id)
    {
        return RechargePaymentsService::allocatePayments($request, $id, 'group');

    }
}
