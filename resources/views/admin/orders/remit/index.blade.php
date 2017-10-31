<div class="box">
    <div class="box-header">

        <h3 class="box-title">
            {!! (new \App\Admin\Extensions\Tools\RemitIfsSelect())->render() !!}
        </h3>

        <div class="pull-right">

        </div>

        <span>
        </span>

    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tr>
                <th> </th>
                <th>ID</th>
                <th>批次号</th>
                <th>商户账户</th>
                <th>出款金额</th>
                <th>单据数量</th>
                <th>单据状态</th>
                <th>提交时间</th>
                <th>操作</th>
            </tr>
            @inject('toPayPresenter','App\Presenters\Admin\ToPayOrderPresenter')
            @foreach($items as $item)
                <tr>
                    <td>
                        <button type="button" data-id="{{$item->id}}" class="expand"><i class="fa fa-minus"></i></button>
                        <button type="button" data-id="{{$item->id}}" class="collapse"><i class="fa fa-plus"></i></button>
                    </td>
                    <td>{{$item->id}}</td>
                    <td>{{$item->batch_no}}</td>
                    <td>{{$item->platuser->username}}</td>
                    <td>{{$item->total_money}}</td>
                    <td>{{$item->num}}</td>
                    <td>{!! $toPayPresenter->batchStatus($item) !!}</td>
                    <td>{{$item->created_at}}</td>
                    <td>{!! (new \App\Admin\Extensions\Actions\RemitOrderAction())->getBatchActions($item->id, $item->status)!!}</td>
                </tr>
                <tr>
                    <td colspan="7">
                        <table class="table table-hover item-table" data-id="{{$item->id}}" style="display: block;padding-left: 20px">
                            <tr>
                                <th><i class="fa fa-angle-down"></i></th>
                                <th>ID</th>
                                <th>系统流水号</th>
                                <th>姓名</th>
                                <th>账号</th>
                                <th>银行</th>
                                <th>省份</th>
                                <th>城市</th>
                                <th>分行</th>
                                <th>出款金额</th>
                                <th>收取手续费</th>
                                <th>实际扣款</th>
                                <th>单据状态</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            @foreach($item->manyChild as $child)
                                <tr>
                                    <td><i class="fa fa-angle-down"></i></td>
                                    <td>{{$child->id}}</td>
                                    <td>{{$child->plat_no}}</td>
                                    <td>{{$child->bk_username}}</td>
                                    <td>{{$child->bk_account}}</td>
                                    <td>{{\App\Lib\BankMap::getMap($child->bk_category)}}</td>
                                    <td>{{$child->bk_prov}}</td>
                                    <td>{{$child->bk_city}}</td>
                                    <td>{{$child->bk_branch}}</td>
                                    <td>{{$child->money}}</td>
                                    <td>{{$child->fee}}</td>
                                    <td>{{$child->ac_money}}</td>
                                    <td>{!! $toPayPresenter->batchStatus($child) !!}</td>
                                    <td>{{$child->updated_at}}</td>
                                    <td>{!! (new \App\Admin\Extensions\Actions\RemitOrderAction())->getItemActions($child->id, $child->status)!!}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="box-footer clearfix">
        {{ $items->links() }}
    </div>
    <!-- /.box-body -->
</div>
