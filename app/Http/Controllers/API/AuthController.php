<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;



class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken]);
    }

    public function getToken(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $auth = User::where('email', $request->email)->where('password', $request->password)->first();

        if (!auth()->attempt($loginData) && !$auth) {
            return response(['message' => 'Invalid Credentials']);
        }

        if ($auth) {
            $accessToken = $auth->createToken('authToken')->accessToken;
        } elseif (auth()->user()) {
            $auth = auth()->user();
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
        }

        return response(['user' => $auth, 'access_token' => $accessToken]);
    }

    public function changePassword(Request $request)
    {
        // Validasi input
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Verifikasi current_password 
        if (!Hash::check($request->current_password, $user->password)) {  // Gunakan $user->password
            return response()->json([
                'message' => 'Current password incorrect.'
            ], 400);
        }

        // Update password baru
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password successfully changed for user with email: ' . $request->email
        ]);
    }
}
