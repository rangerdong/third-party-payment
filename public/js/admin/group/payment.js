$("select[name='payment']").on('change', function () {
    console.log($(this).val());
});

$("button#add-single, button#add-full").on('click', function () {
    var add_type = $(this).attr('data-type');
    $(this).attr('disabled', true);
    if (add_type == 'single') {
        //如果是单次添加，获取类型框中数据
        var payment_id = $("select[name='payment']").val();
        $.get('/admin/api/group/pmadd', {'id': payment_id}, function (data) {

        });
    } else {
        $.get('/admin/api/group/pmadd', {'id':0}, function (data) {
            
        })
    }
});
