<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\SettlePaymentAddAll;
use App\Admin\Extensions\Tools\UserGender;
use App\Models\DictPayment;
use App\Models\SettlePayment;

use App\Models\SettleSplitMode;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SettlePaymentController extends Controller
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

            $content->header('结算通道管理');
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

            $content->header('结算通道修改');
            $content->description('description');

            $content->body($this->form($id)->edit($id));
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

            $content->header('结算通道新增');
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
        return Admin::grid(SettlePayment::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('dictpayment.name', '通道名称');
            $grid->column('dictpayment.identify', '通道标识');
            $grid->column('splitmode.name', '处理模式');
            $grid->support('api支持')->switch([
                'on' => ['value' => 1, 'text' => '支持'],
                'off' => ['value' => 0, 'text' => '不支持']
            ]);

            $grid->created_at();
            $grid->updated_at();
            if (DictPayment::settle()->count() != SettlePayment::count()) {
                $grid->tools(function ($tools) {

                    $tools->append(new SettlePaymentAddAll());
                });
            }

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        return Admin::form(SettlePayment::class, function (Form $form) use ($id) {

            $payments = DictPayment::settle()->get();
            $form->display('id', 'ID');
            if ( ! $id) {
                $form->select('dict_id', '通道类型')
                    ->options($payments->pluck('name', 'id'))
                    ->load('mode_id', route('api.splitmode.settle'))
                    ->rules('required|unique:settlement_payments');
            } else {
                $form->select('dict_id', '通道类型')
                    ->options($payments->pluck('name', 'id'))
                    ->readOnly();
            }

            $form->switch('support', '是否支持api')->options([
                'on' => ['value' => 1, 'text' => '支持'],
                'off' => ['value' => 0, 'text' => '不支持']
            ]);
            $form->select('mode_id', '处理模式id')
                ->options(
                    SettleSplitMode::where('pm_id', $id ? SettlePayment::find($id)->dict_id : $payments[0]->id)
                        ->orderBy('is_default', 'desc')->pluck('name', 'id')
                );
            $form->saving(function ($form) {
                if ($form->mode_id == null) $form->mode_id = 0;
            });
        });
    }
}
