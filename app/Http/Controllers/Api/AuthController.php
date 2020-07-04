<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateLoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param ValidateLoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(ValidateLoginRequest $request)
    {
        $credentials['email'] = $request->email;
        $credentials['password'] = $request->password;

        if($token = auth('api')->attempt($credentials)) {
            return $this->createNewToken($token);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['logout' => true]);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 201);
    }
}
