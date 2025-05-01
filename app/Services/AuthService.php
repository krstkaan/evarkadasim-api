<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class AuthService
{
    public function register(Request $request): array
    {
        $user = User::create([
            'name' => $request->adsoyad,
            'email' => $request->email,
            'telefon' => $request->telefon,
            'password' => Hash::make($request->password),
            'onayli' => true,
        ]);

        $token = JWTAuth::fromUser($user);

        return ['user' => $user, 'token' => $token];
    }

    public function login(array $credentials): array|bool
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return false;
        }

        return [
            'user' => auth()->user(),
            'token' => $token,
        ];
    }

    public function me(): mixed
    {
        return auth()->user();
    }

    public function logout(): void
    {
        auth()->logout();
    }
}
