$("select[name='type']").change(function () {
    $.get('/admin/api/splitmode/')
});
