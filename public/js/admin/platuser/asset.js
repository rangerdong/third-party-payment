function refreshDate() {
    $("a.grid-refresh").on('click', function () {
         $.get('/admin/api/asset/refresh', function (data) {
             if (data.code == 0) {
                 layer.msg('更新成功');
                 window.location.reload();
             }
         })
    });
}
