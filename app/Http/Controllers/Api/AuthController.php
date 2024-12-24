<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController
{
    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();

        $accessToken->revoke();


        return response()->json([
            'message' => 'Logout exitoso. Token revocado.'
        ], 200);
    }
}
