<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $request->validate([
            'adsoyad' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'telefon' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $result = $this->authService->register($request);

        return response()->json($result, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($request->only('email', 'password'));

        if (!$result) {
            return response()->json(['error' => 'Geçersiz giriş bilgileri'], 401);
        }

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['code']);
        }

        return response()->json($result);
    }

    public function me()
    {
        return response()->json($this->authService->me());
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'telefon' => 'nullable|string|max:20',
            'dogum_tarihi' => 'nullable|date',
            'gender' => 'nullable|in:male,female,non_binary,prefer_not_to_say',
            'il_id' => 'nullable|exists:ililcemahalle,id',
            'ilce_id' => 'nullable|exists:ililcemahalle,id',
        ]);


        $user = $this->authService->updateProfile($request);
        return response()->json(['message' => 'Profil güncellendi', 'user' => $user]);
    }
    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $result = $this->authService->uploadProfilePhoto($request);

        return response()->json($result);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Başarıyla çıkış yapıldı']);
    }
}
