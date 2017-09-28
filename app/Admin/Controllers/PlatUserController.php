<?php

namespace App\Admin\Controllers;

use App\Models\PlatUser;

use App\Models\RechargeGroup;
use App\Presenters\Admin\PlatUserPresenter;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PlatUserController extends Controller
{
    use ModelForm;

    protected $user = null;
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
        $this->user = PlatUser::find($id);

        return Admin::content(function (Content $content) use ($id) {

            $plat_user = PlatUser::find($id);

            $content->header(" {$plat_user->username}修改");
            $content->description("{$plat_user->code}");

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
        $this->user = null;
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
            $grid->column('profile.property', '账户性质')->display(function ($type) {
                return $type === null ? '-' : ($type ? '企业' : '个人');
            });
            $grid->code('用户编号');
            $grid->role('账户类型')->display(function ($role) {
                return config('dictionary.user_roles.'.$role);
            });
            $grid->username('用户名');
            $grid->phone('手机号');
            $grid->status('账户状态')->display(function ($status) {
                return PlatUserPresenter::showStatus($status);
            });
            $grid->column('upper.username', '上级用户')->display(function ($value) {
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
        return Admin::form(PlatUser::class, function (Form $form) {
            Admin::script('initSelect();');

            $form->tab('基本信息', function ($form) {
                $form->display('code', '用户编号');
                $form->text('username', '登录邮箱')->placeholder('输入登录邮箱')
                    ->rules(function ($form) {
                        if (!$id = $form->model()->id) {
                            return 'required|max:100|unique:plat_users,username|email';
                        } else {
                            return 'required|max:100|email';
                        }
                    });
                $form->text('password', '用户密码')
                    ->placeholder($form->model()->id ? '输入新密码更改密码': '输入登录密码');
                $form->text('phone', '联系手机')->rules('required|regex:/^1[34578][0-9]{9}$/');
                $form->select('status', '账户状态')->options([
                    -1 => '停用',
                    0 => '未审核',
                    1 => '已审核',
                    2 => '审核拒绝'
                ])->default(0)->rules('required');
            })->tab('风控信息', function ($form) {
                $form->select('role', '用户角色')
                    ->options(config('dictionary.user_roles'))
                    ->default(0);
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
                $form->select('recharge_gid', '交易分组')
                    ->options(RechargeGroup::where('classify', 0)
                        ->orderBy('is_default', 'desc')
                        ->pluck('name', 'id'))->rules('required');
                if (($this->user && $this->user->role == 0) || $this->user === null) {
                    $form->select('upper_id', '上级')
                        ->options(array_merge(
                            [0 => '无'],
                            PlatUser::proxy()->audited()->get()->pluck('username', 'id')->toArray()
                        ))->default(0);
                }
            });

            if ($this->user && $this->user->role == 1) {
                $form->ignore(['upper_id']);
            }
            $form->saving(function (Form $form) {
                if ($form->password) {
                    $form->password = password_hash($form->password, PASSWORD_DEFAULT);
                } else {
                    $form->password = $form->model()->password;
                }
            });
        });
    }
}
