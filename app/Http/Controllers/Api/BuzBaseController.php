<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class BuzBaseController extends Controller
{

    public function getUserFromJWT()
    {
        return JWTAuth::parseToken()->authenticate();
    }
}
