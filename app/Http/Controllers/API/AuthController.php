<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;


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
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',  // Memastikan user_id valid
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // // Verifikasi bahwa admin yang login
        // $admin = auth()->user();
        // if (!$admin || !$admin->is_admin) {
        //     return response(['message' => 'Unauthorized'], 403);  // Pastikan admin yang login
        // }

        // Cari pengguna yang ingin diubah password-nya
        $user = User::find($request->user_id);
        
        // Verifikasi password lama jika diperlukan (opsional, jika ingin admin melakukan perubahan tanpa password lama)
        if ($request->has('current_password') && !Hash::check($request->current_password, $user->password)) {
            return response(['message' => 'Current password is incorrect.'], 400);
        }

        // Update password baru
        $user->password = bcrypt($request->password);
        $user->save();

        return response(['message' => 'Password successfully changed for user ID ' . $request->user_id]);
    }
}
