var initSelect = function () {
    $('select[name=role], select[name=recharge_mode]').change(function () {

        var role = $('select[name=role]').val();
        var mode = $('select[name=recharge_mode]').val();
        var group = $('select[name=recharge_gid]');

        var id = $('input[name="id"]').val();
        if (mode != 0) {
            $.get('/admin/api/group/rechargemode', {'role': role, 'mode': mode, 'id': id}, function (data) {
                if (data.code == 0) {
                    var groups = data.data.groups;
                    var len = groups.length;
                    var options = '';
                    console.log(groups);
                    if (len == 0) {
                        options = '<option value="0" selected> 无分组 </option>';
                    } else {
                        for (var i = 0; i < len; i++) {
                            options += groups[i]['is_default'] == 1
                                ? '<option value="' + groups[i].id + '" selected> ' + groups[i].name + '</option>'
                                : '<option value="' + groups[i].id + '"> ' + groups[i].name + '</option>';
                        }
                    }
                    group.html(options);
                }
            })
        } else {
            group.html('<option value="0" selected> 个人分组 </option>');
        }
    });

    $("")
}

//user profile 使用
var global_group = '';
$('select[name="platuser[recharge_mode]"]').change(function () {
    var type = $(this).val();
    var groups = $('select[name="platuser[recharge_gid]"]');
    var single_group = "<option value=0 selected>个人分组</option>";
    if (type == 0) {
        global_group = groups.html();
        groups.html(single_group);
    } else {
        groups.html(global_group);
    }
});

function auditRefuse() {
    var id = $("input[name=profile_id]").val();
    console.log(id);
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
                window.location.href = '/admin/profiles';
            } else {
                swal('内部错误', data.message, 'error');
            }
        })
    })

}

function updateButton() {
    var submit = $('form button[type=submit]');
    submit.text('审核通过并保存');
    var refuse = '<div class="btn-group pull-left"><a onclick="auditRefuse()" class="btn btn-danger pull-left">审核拒绝</a></div>';
    submit.parent().parent().append(refuse);
}


