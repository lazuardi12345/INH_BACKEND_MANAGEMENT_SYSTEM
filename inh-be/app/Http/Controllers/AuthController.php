<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Fungsi untuk registrasi user baru
    public function register(Request $request) {
        $input = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        User::create($input);

        $data = [
            'message' => 'User is created successfully'
        ];

        return response()->json($data, 200);
    }

    // Fungsi untuk login
    public function login(Request $request) {
        
        $input = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // Mengambil data user dari database berdasarkan email
        $user = User::where('email', $input['email'])->first();

        // Membandingkan input user dengan data user di database
        $isLoginSuccessfully = (
            $user && // Pastikan user ditemukan
            Hash::check($input['password'], $user->password) 
        );

        if ($isLoginSuccessfully) {
            // Membuat token
            $token = $user->createToken('auth_token');

            $data = [
                'message' => 'Login successfully',
                'token' => $token->plainTextToken,
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Username or Password is wrong'
            ];

            return response()->json($data, 401);
        }
    }
}
