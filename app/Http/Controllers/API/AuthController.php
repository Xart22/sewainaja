<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json([
            'message' => 'Success',
            'token' => $token,
            'user' => $user
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token deleted'
        ]);
    }

    public function me()
    {

        return response()->json([
            'message' => 'Success',
            'user' => Auth::user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->user()->update($request->all());

        return response()->json([
            'message' => 'Profile updated'
        ]);
    }
}
