<?php

namespace App\Admin\Controllers;

use App\Models\PlatUser;

use App\Models\RechargeGroup;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class PlatUserController extends Controller
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

            $content->header('平台用户管理 列表');
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
            $plat_user = PlatUser::find($id);
            $user_role = config('dictionary.user_roles'.$plat_user->role);

            $content->header("[{$user_role}] {$plat_user->code}修改");
            $content->description("{$plat_user->email} / {$plat_user->phone}");

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

            $content->header('用户新增');
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
        return Admin::grid(PlatUser::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->type('账户类型')->display(function ($type) {
                return $type ? '企业' : '个人';
            });
            $grid->code('用户编号')->sortable();
            $grid->role('所属类型')->display(function ($role) {
                return config('dictionary.user_roles'.$role);
            });
            $grid->username('用户名');
            $grid->email('邮箱');
            $grid->phone('手机号');
            $grid->status('账户状态')->display(function ($status) {
                switch ($status) {
                    case -1:
                        return '停用';break;
                    case 0:
                        return '未审核';break;
                    case 1:
                        return '已审核'; break;
                    default:break;
                }
            });
            $grid->column('upper.code', '上级用户')->display(function ($value) {
                return $value ? : '-';
            });
            $grid->created_at('注册时间');
            $grid->last_at('上次登录时间');
            $grid->last_ip('上次登录ip');

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        Admin::js('js/admin/platuser/add.js');
        return Admin::form(PlatUser::class, function (Form $form) {

            $form->display('code', '商户编码');
            $form->select('role', '用户角色')
                ->options(config('dictionary.user_roles'))
                ->default(0);
            if ($form->model()->id) {
                $form->display('code', '用户编号');
            }
            $form->text('username', '用户名')->placeholder('输入用户名 email格式')
                ->rules('required|max:100|unique:plat_users,username|email');
            $form->password('password', '用户密码')->default('');
            $form->select('type', '账户类型')->options([
                0 => '个人',
                1 => '企业'
            ])->default(1)->rules('required');
            $form->select('status', '账户状态')->options([
                -1 => '停用',
                0 => '未审核',
                1 => '已审核'
            ])->default(0)->rules('required');
            $form->display('key', '商户密钥')
                ->help("<a id='update-key' disabled=".($form->model()->id ? 'false': 'true')."><i class='fa fa-refresh'></i> 更新密钥</a>", '');
            $audit_status = [
                0 => '未验证',
                1 => '已验证',
            ];
            $form->radio('audit_email', '邮箱认证')->options($audit_status)->default(0);
            $form->radio('audit_phone', '手机认证')->options($audit_status)->default(0);
            $form->radio('audit_realname', '实名认证')->options($audit_status)->default(0);
            $form->radio('audit_company', '企业信息认证')
                ->options($audit_status)
                ->default(0);
            $form->radio('audit_domains', '域名认证')->options($audit_status)->default(0);
            $form->radio('is_withdraw', '允许提现')->options([
                0 => '不允许',
                1 => '允许'
            ])->default(0);
            $form->radio('settle_type', '结算类型')->options([
                0 => '平台结算',
                1 => 'api结算'
            ]);
            $form->radio('recharge_api', '支付api功能')->options([
                0 => '未开通',
                1 => '已开通'
            ]);
            $form->radio('settle_api', '结算api功能')->options([
                0 => '未开通',
                1 => '已开通'
            ]);
            $form->select('settle_cycle', '结算周期')->options([
                0 => 't+0',
                1 => 't+1',
                7 => 't+7'
            ]);
            $form->select('recharge_mode', '交易模式')->options([
                0 => '按个人',
                1 => '按分组'
            ])->default(1)->rules('required');
//            if ($form->model()->recharge_mode != 0) {
            $form->select('recharge_gid', '交易分组')
                ->options(RechargeGroup::where('classify', 0)
                    ->orderBy('is_default', 'desc')
                    ->pluck('name', 'id'))->rules('required');
//            }

            $form->saving(function (Form $form) {
                $form->password = password_hash($form->password, PASSWORD_DEFAULT);
                $form->code = generateRandomCode($form->password);
//                $form->key = generateRandomCode()
            });

        });
    }
}
