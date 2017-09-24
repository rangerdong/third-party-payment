$('select[name=role], select[name=recharge_mode]').change(function () {

    var role = $('select[name=role]').val();
    var mode = $('select[name=recharge_mode]').val();
    var group = $('select[name=recharge_gid]');

    var id = $('input[name="id"]').val();
    console.log(group, id, role, mode);
    if (mode != 0) {
        $.get('/admin/api/group/rechargemode', {'role': role, 'mode': mode, 'id': id}, function (data) {
            if (data.code == 0) {
                var groups = data.data.groups;
                var len = groups.length;
                var options = '';
                console.log(groups);
                if (len == 0) {
                    options = '<option value="0"> 无分组 </option>';
                } else {
                    for (var i = 0; i < len; i++) {
                        options += '<option value="' + groups[i].id + '"> ' + groups[i].name + '</option>';
                    }
                }
                group.html(options);
            }
        })
    } else {
        group.html('<option value="0"> 个人分组 </option>');
    }
});

