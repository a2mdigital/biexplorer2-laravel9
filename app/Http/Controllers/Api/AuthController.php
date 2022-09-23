<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('api')->setTTL(Carbon::now()->addDays(365)->timestamp)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['response' => 'ok', 'message' => 'Successfully logged out']);
    }

    //LOGIN PARCEIRO API
    public function loginParceiro(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        return $credentials;
        if (!$token = auth('apiParceiro')->setTTL(Carbon::now()->addDays(365)->timestamp)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithTokenParceiro($token);
    }

    public function meParceiro()
    {
        return response()->json(auth('apiParceiro')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutPArceiro()
    {
        auth('apiParceiro')->logout();

        return response()->json(['response' => 'ok', 'message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        $date = Carbon::createFromTimestamp(auth('api')->factory()->getTTL())->format("Y-m-d H:m");
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  $date
  
        ]);
    }
    protected function respondWithTokenParceiro($token)
    {
        $user = auth('apiParceiro')->user();
        return $user;
        //pego a data que foi setada para mostrar quando o token irá expirar
        $date = Carbon::createFromTimestamp(auth('apiParceiro')->factory()->getTTL())->format("Y-m-d H:m");
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  $date
  
        ]);
    }
}
