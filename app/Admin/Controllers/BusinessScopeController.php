<?php

namespace App\Admin\Controllers;

use App\Models\BusinessScope;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class BusinessScopeController extends Controller
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

            $content->header('企业经营范围管理');
            $content->description('经营列表');

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

            $content->header(BusinessScope::find($id).'范围修改');
            $content->description();

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

            $content->header('范围添加');
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
        return Admin::grid(BusinessScope::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('范围名称');
            $grid->column('upper.name', '上级类目')->display(function ($name) {
                return $name ? : '-';
            });
            $grid->index('排序');

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
        return Admin::form(BusinessScope::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->select('parent', '上级类目')
                ->options(array_merge(
                    [0=>'一级类目'],
                    BusinessScope::where('depth', 0)->get()->pluck('name', 'id')->toArray()
                ))->default(0)->rules('required');
            $form->text('name', '范围名称')->rules('required|max:500');
            $form->hidden('depth', '层级')->default(0);
            $form->number('index', '排序')->default(0);

            $form->saving(function ($form) {
                if ($form->parent != 0) {
                    $form->depth = 1;
                }
            });
        });
    }
}
