<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return response()->json(['code' => 422, 'message' => 'maybe email or password wrong'], 422);
        }

        $accessToken = auth()->user()->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $accessToken]);
    }
}
