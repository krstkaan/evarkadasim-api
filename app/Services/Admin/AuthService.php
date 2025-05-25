<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Admin kullanıcısını doğrular, 'is_heilos' kontrolü yapar ve token üretir.
     *
     * @param array $credentials ['email' => string, 'password' => string]
     * @return array|false Dizi: ['user' => User, 'token' => string], Hata durumunda false.
     */
    public function login(array $credentials): array|false
    {
        // 1. Kimlik Bilgilerini Doğrula (JWTAuth ile)
        if (!$token = JWTAuth::attempt($credentials)) {
            // Genel giriş hatası (yanlış email/şifre)
            return false;
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user || $user->is_helios != 1) {
            // Token'ı context'e set et, sonra invalidate et
            JWTAuth::setToken($token);
            JWTAuth::invalidate($token);
            return false;
        }

        // 3. Başarılı: Kullanıcı ve Token'ı Döndür
        return [
            'user' => $user->makeHidden(['email_verified_at', 'created_at', 'updated_at']),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }

    /**
     * Mevcut admin token'ını geçersiz kılar (Logout).
     *
     * @return bool
     */
    public function logout(): bool
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}