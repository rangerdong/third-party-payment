<div class="box">
    <div class="box-header">

        <h3 class="box-title"></h3>

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
                <th>ID<a class="fa fa-fw fa-sort" href="http://www.payment.com/admin/orders/remit/audit?_sort%5Bcolumn%5D=id&amp;_sort%5Btype%5D=desc"></a></th>
                <th>批次号</th>
                <th>商户账户</th>
                <th>出款金额</th>
                <th>单据状态</th>
                <th>操作</th>
            </tr>
            @foreach($items as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->branch_no}}</td>
                    <td>{{$item->platuser->username}}</td>
                    <td>{{$item->total_money}}</td>
                    <td>{{$item->status}}</td>
                    <td>1231</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="box-footer clearfix">
        {{ $items->links() }}
    </div>
    <!-- /.box-body -->
</div>

