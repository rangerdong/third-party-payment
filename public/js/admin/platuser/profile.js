

layui.use('layer', function () {
    var $ = layui.jquery,
    layer = layui.layer;

});

function showProfile(id) {
    layer.open({
        type: 2,
        title: '认证资料',
        area: ['80%', '90%'],
        content: '/admin/profiles/detail/'+id
    });
}

function auditPass(id) {
    $.post('/admin/api/profiles/audit/'+id, {'type' : 'pass'}, function (data) {
        if (data.code == 0) {
            swal('审核通过', '', 'success');
            window.location.reload();
        } else {
            swal('内部错误', data.message, 'error');
        }
    })
}

function auditRefuse(id) {
    swal({
        title: '输入拒绝理由',
        text: '请输入拒绝的详细理由',
        type:'input',
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        animation: "slide-from-top",
        inputPlaceholder: "理由"
    }, function (value) {
        if (value === false) return false;
        if (value === "") {
            swal.showInputError('需要输入理由');
            return false
        }
        $.post('/admin/api/profiles/audit/'+id, {'type':'refuse', 'reason': value}, function (data) {
            if (data.code == 0) {
                swal('资料审核已驳回', '', 'success');
                window.location.reload();
            } else {
                swal('内部错误', data.message, 'error');
            }
        })
    })

}


