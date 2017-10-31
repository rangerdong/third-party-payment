<?php
namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\Code;
use App\Repositories\Contracts\RemitOrderRepository;
use App\Repositories\Eloquent\AssetCountEloquent;
use App\Repositories\Eloquent\RemitOrderRepositoryEloquent;
use App\Services\ApiResponseService;
use App\Services\RemitOrderService;
use Illuminate\Http\Request;

class RemitOrderController extends Controller
{
    protected $remitRepository;

    public function __construct(RemitOrderRepositoryEloquent $remitOrderRepository)
    {
        $this->remitRepository = $remitOrderRepository;
    }

    public function operate(AssetCountEloquent $assetCountEloquent, Request $request)
    {
        $type = $request->input('audit');
        $id = $request->input('id');
        $reason = $request->input('reason');
        try {
            switch ($type) {
                case 'pass':
                    $this->remitRepository->auditPass($id); break;
                case 'refuse':
                    $this->remitRepository->auditRefuse($id, $reason); break;
                case 'remitted':
                    $this->remitRepository->remitted($id); break;
                case 'remit':
                    $this->remitRepository->remit($id); break;

            }
            return ApiResponseService::success(Code::SUCCESS);
        } catch (\Exception $exception) {
            return ApiResponseService::showError(Code::FATAL_ERROR, $exception->getMessage());
        }
    }
}
