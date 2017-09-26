<?php

namespace App\Admin\Controllers;

use App\Models\PlatUserProfile;

use App\Presenters\Admin\PlatUserPresenter;
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
//        Admin::js('vendor/layui/layui.js');
//        Admin::css('vendor/layui/css/layui.css');
        Admin::js('js/admin/platuser/profile.js');
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
                return "<a  class='show-profile' onclick='showProfile({$this->id})' data-id='{$this->id}' data-type='realname'>".$this->realname. "<br/>".$this->idcard."<br/>手持证件照<br/>证件照背面<br/>证件照正面"."</a>";
            });
//            $grid->column('scope', '经营范围');
            $grid->column('enterprise_profile', '企业认证资料')->display(function (){
                return $this->property !=1 ? '-' : "<a onclick='showProfile({$this->id})' class='show-profile' data-id='{$this->id}' data-type='enterprise'>".$this->enterprise . "<br/>经营范围</a>";
            });
            $grid->column('platuser.status', '用户状态')->display(function ($status) {
                return PlatUserPresenter::showStatus($status);
            });
            $grid->created_at('提交时间');
            $grid->disableCreation();
            $grid->actions(function ($actions){
                $actions->append('<a title="审核通过"  onclick="auditPass('.$actions->getKey().')" data-type="pass" href="javascript:void(0);"><i class="fa fa-check-circle-o"></i></a>');
                $actions->append('<a title="审核拒绝"  onclick="auditRefuse('.$actions->getKey().')" data-type="refuse" href="javascript:void(0);"><i class="fa fa-times-circle-o"></i> </a>');
            });
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

    public function showProfile($id)
    {
        $profile = PlatUserProfile::find($id);
        return view('admin.platuser.profile', compact('profile'));

    }
}
