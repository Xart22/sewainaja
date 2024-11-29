<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        User::where('id', Auth::id())->update([
            'fcm_token' => null,
        ]);

        $request->user()->tokens()->delete();
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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => 'required',
        ]);

        $user = User::find(Auth::id());

        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password not match'
            ], 401);
        }

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password updated'
        ]);
    }
}
