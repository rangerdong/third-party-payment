<?php

namespace App\Admin\Controllers;

use App\Models\AssetCount;

use App\Models\PlatUser;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class AssetCountController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        if (PlatUser::count()  != AssetCount::count()) {
            foreach (PlatUser::all() as $user) {
                AssetCount::firstOrCreate(['uid' => $user->id]);
            }
        }
        return Admin::content(function (Content $content) {

            $content->header('用户资金管理');
            $content->description('');

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

            $platuser = AssetCount::find($id)->platuser;
            $content->header($platuser->username. ' 资金信息');
            $content->description($platuser->code);

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
        return Admin::grid(AssetCount::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('platuser.username', '用户账户');
            $grid->column('total','总资金')->sortable();
            $grid->column('available', '可用资金')->sortable();
            $grid->column('recharge_settle', '交易冻结/提现冻结')->display(function () {
                return $this->recharge_frozen . '/' . $this->withdraw_frozen;
            });
            $grid->column('other_frozen', '其余冻结')->editable('text');
            $grid->disableCreation();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(AssetCount::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->display('platuser.username', '用户账户');
            $form->display('total', '总资金');
            $form->currency('available', '可用资金')
                ->options(['digits' => 6])
                ->help('最大长度16位')
                ->rules('required|max:17');
            $form->currency('recharge_frozen', '交易冻结资金')
                ->options(['digits' => 6])
                ->help('最大长度16位')
                ->rules('required|max:17');
            $form->currency('withdraw_frozen', '提现冻结资金')
                ->options(['digits' => 6])
                ->help('最大长度16位')
                ->rules('required|max:17');
            $form->currency('other_frozen', '其他冻结资金')
                ->options(['digits' => 6])
                ->help('最大长度16位')
                ->rules('required|max:17');

        });
    }
}
