<?php
namespace App\Admin\Extensions\Actions;

class AuditRow
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
