<?php
namespace App\Services;

use App\Repositories\Contracts\RemitOrderRepository;

class RemitOrderService
{
    protected $remitOrderRepository;

    public function __construct(RemitOrderRepository $remitOrderRepository)
    {
        $this->remitOrderRepository = $remitOrderRepository;
    }

    public function auditPass($id)
    {
        $this->remitOrderRepository->auditPass($id);
    }
}
