<?php

namespace App\Admin\Controllers;

use App\Models\PlatUser;
use \App\Models\PlatUserApp;

use App\Presenters\Admin\PlatUserAppPresenter;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PlatUserAppController extends Controller
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

            $content->header('用户应用管理');
            $content->description('应用列表');

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
            $app = PlatUserApp::find($id);
            $headedesc = PlatUserAppPresenter::editHeaderAndDesc($app);

            $content->header($headedesc['header']);
            $content->description($headedesc['desc']);

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

            $content->header('应用创建');
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
        return Admin::grid(PlatUserApp::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('platuser.username', '所属用户');
            $grid->name('应用名称');
            $grid->app_id('APP_ID');
            $grid->column('classify', '应用类型')->display(function ($classify) {
                return config('dictionary.user_apps.'.$classify);
            });
            $grid->column('status', '应用状态')->display(function ($status) {
                return PlatUserAppPresenter::showStatus($status);
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
    protected function form($id = null)
    {
        return Admin::form(PlatUserApp::class, function (Form $form) use ($id) {

            $form->display('id', 'ID');
            if ($id === null) {
                $form->select('uid', '创建账户')->options(PlatUser::all()->pluck('username', 'id'))->rules('required');
            } else {
                $form->display('platuser.username', '创建账户');
            }
            $form->display('app_id', '应用id');
            if ($id === null) {
                $form->text('name', '应用名称');
                $form->select('classify', '应用类型')->options(config('dictionary.user_apps'))->rules('required');
                $form->text('domain', '应用域名/安卓签名/iosbundleid');
                $form->text('icp', 'icp备案号/安卓包名');
            } else {
                $form->display('name', '应用名称');
                $form->select('classify', '应用类型')->options(config('dictionary.user_apps'))->readOnly();
                $form->display('domain', '应用域名/安卓签名/iosbundleid');
                $form->display('icp', 'icp备案号/安卓包名');
            }

            $form->select('status', '应用状态')
                ->options(config('status.apps'))
                ->default(0)
                ->rules('required');
            $form->textarea('remark', '备注信息')->rules('nullable|max:500')->help('拒绝应用通过时作为拒绝理由展示给用户端');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
            $form->saving(function ($form) use ($id){
                if ($id === null) {
                    $form->app_id = generateAppCode($form->classify);
                }
            });
        });
    }
}
