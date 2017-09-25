<?php

namespace App\Admin\Controllers;

use App\Models\PlatUserProfile;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PlatUserProfileController extends Controller
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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(PlatUserProfile::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('platuser.username', '商户账户');
            $grid->column('property', '商户性质')->display(function ($property) {
                return $property ? '企业': '个人';
            });
            $grid->column('role', '认证角色')->display(function ($role) {
                return $role == 1 ? '代理' :'商户';
            });
            $grid->column('auth_profile', '实名资料')->display(function () {
                return $this->realname. "<br/>手持证件照<br/>证件照背面<br/>证件照正面";
            });
//            $grid->column('scope', '经营范围');
            $grid->column('enterprise_profile', '企业认证资料')->display(function (){
                return $this->enterprise . "<br/>经营范围";
            });
            $grid->column('platuser.status', '审核状态');
            $grid->created_at();
            $grid->updated_at();
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
        return Admin::form(PlatUserProfile::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
