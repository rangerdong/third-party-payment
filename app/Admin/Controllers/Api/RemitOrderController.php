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

    public function operate(Request $request)
    {
        $type = $request->input('audit');
        $id = $request->input('id');
        $reason = $request->input('reason');
        $settle = $request->input('settle');
        try {
            switch ($type) {
                case 'pass':
                    $this->remitRepository->auditPass($id); break;
                case 'refuse':
                    $this->remitRepository->auditRefuse($id, $reason); break;
                case 'remitted':
                    $this->remitRepository->itemRemitted($id, true); break;
                case 'remit':
                    $this->remitRepository->itemRemit($id); break;
                case 'revoke':
                    $this->remitRepository->itemRevoke($id); break;
                case 'return':
                    $this->remitRepository->itemReturn($id); break;
                default:break;
            }
            return ApiResponseService::success(Code::SUCCESS);
        } catch (\Exception $exception) {
            return ApiResponseService::showError(Code::FATAL_ERROR, $exception->getMessage());
        }
    }
}
