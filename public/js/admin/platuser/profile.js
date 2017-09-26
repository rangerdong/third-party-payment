

layui.use('layer', function () {
    var $ = layui.jquery,
    layer = layui.layer;

    $("a.show-profile").on('click', function () {
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var title = type == 'realname' ? '实名认证资料': '企业认证资料';
        layer.open({
            type: 2,
            title: title,
            area: ['80%', '90%'],
            content: '/admin/profiles/'+id+'/'+type
        })
    });

});


