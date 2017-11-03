<?php
namespace App\Http\Controllers\Api\Buz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $token = null;
        if ( ! $token = JWTAuth::attempt($credentials)) {

        }
        dd($token);
    }
}
