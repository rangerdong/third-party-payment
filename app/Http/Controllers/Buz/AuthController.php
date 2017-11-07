<?php
namespace App\Http\Controllers\Buz;

use App\Http\Controllers\Controller;
use App\Models\PlatUserTmp;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register()
    {

    }

    public function doRegister(Request $request)
    {
        $credentials = $request->only('token', 'username');
        $tmp = PlatUserTmp::where($credentials)->where('expired_at', '>=', time())->first();
        return view('buz.auth.doRegister', [
            'tmp' => $tmp,
        ]);
    }

    public function login()
    {

    }
}
