<?php
namespace App\Presenters\Admin;

class ToPayOrderPresenter
{
    public function itemScript()
    {
        $script = <<<script
$('button.collapse').on('click', function() {
    var id = $(this).data('id');
    console.log(id)
    $('button.expand[data-id='+id+']').show();
    $(this).hide();
    $('.item-table[data-id='+id+']').show();
});

$('button.expand').on('click', function() {
    var id = $(this).data('id');
    $('button.collapse[data-id='+id+']').show();
    $(this).hide();
    $('.item-table[data-id='+id+']').hide();
});
script;
        return $script;
    }
}
