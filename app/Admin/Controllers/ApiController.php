<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CyInterface;
use App\Models\PoInterface;

class ApiController extends Controller
{
    public function interfaces(Request $request, $type)
    {
            $model = $type == 'cy'
                ? new CyInterface()
                : new PoInterface();
            return array_merge([['id' => 0, 'text' => '无']], $model->get(['id', 'name as text'])->toArray());
    }
}
