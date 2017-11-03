<?php

namespace App\Admin\Controllers;

use App\Lib\ThirdPartyMap;
use App\Models\DictInterface;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Table;

class DictInterfaceController extends Controller
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

            $content->header('接口商字典 管理');
            $content->description('字典列表');

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

            $content->header('接口商字典修改');
            $content->description(DictInterface::find($id)->name.' 修改');

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

            $content->header('接口商字典 添加');
            $content->description('新增');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     */
    protected function grid()
    {
        $headers = ['接口商', '接口商标识'];
        $maps = ThirdPartyMap::getMap();
        $rows = [];
        foreach ($maps as $key => $map) {
            $rows[] = [$key, $map];
        }
        $table = new Table($headers, $rows);
        return $table->render();

//        return Admin::grid(DictInterface::class, function (Grid $grid) {
//
//            $grid->id('ID')->sortable();
//            $grid->name('接口商名称');
//            $grid->identify('接口商编码');
//            $grid->actions(function ($actions) {
//                $actions->disableDelete();
//            });
//
//        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(DictInterface::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name', '接口商名称');
            $form->text('identify', '接口商编码');

            $form->display('updated_at', '修改时间');
        });
    }
}
