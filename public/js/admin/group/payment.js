$("select[name='payment']").on('change', function () {
    console.log($(this).val());
});

$("button#add-single, button#add-full").on('click', function () {
    var add_type = $(this).attr('data-type');
    var group_id = $(this).attr('data-id');
    var payment_id = add_type == 'single' ? $("select[name='payment']").val(): 0;
    if ($("select[name='payment']").val() == null) {
        swal('无可添加通道！', '', 'error');
        return true;
    }
    $(this).attr('disabled', true);
    $.get('/admin/api/group/'+group_id+'/pmadd', {'payment_id': payment_id}, function (data) {
        $(this).attr('disabled',alse);
        if (data.code == 0) {
            swal('添加成功', "通道添加成功", "success");
            window.location.reload();
        } else {
            swal('添加失败', data.message, "error");
        }
    });
});

$("button#save").on('click', function () {
    var fields = $("div.col-md-12 form");
    swal({
        title:'保存中...',
        showConfirmButton: false
    });
    $.post('', fields.serializeArray(), function (data) {
        if (data.code == 0) {
            swal('保存成功', "全部保存成功", "success");
            window.location.reload();
        } else {
            swal('添加失败', data.message, "error");
        }
    })
});
