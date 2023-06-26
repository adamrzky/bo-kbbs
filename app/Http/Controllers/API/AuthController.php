<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

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
}
