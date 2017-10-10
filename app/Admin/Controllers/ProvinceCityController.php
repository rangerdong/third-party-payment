<?php

namespace App\Admin\Controllers;

use App\Models\ProvinceCity;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ProvinceCityController extends Controller
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

            $content->header('省市数据配置');
            $content->description('省市列表');

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

            $pcity = ProvinceCity::find($id);
            $content->header($pcity->name.'修改');
            $content->description($pcity ? '省份:'. $pcity->province : '');

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

            $content->header('省市新增数据');
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
        return Admin::grid(ProvinceCity::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('name', '名称');
            $grid->column('upper.name', '上级');
            $grid->index('序号');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ProvinceCity::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->select('parent', '省级')
                ->options(array_merge(
                    [0 => '省级'],
                    ProvinceCity::where('depth', 0)
                        ->get()
                        ->pluck('name', 'id')->toArray())
                );
            $form->text('name', '名称')->rules('required|max:200');
            $form->number('index', '序号')->default(0);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
