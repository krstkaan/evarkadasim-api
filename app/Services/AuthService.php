<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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

        $user = auth()->user();

        if (!$user->onayli) {
            auth()->logout(); // Token'ı iptal et
            return [
                'error' => 'Hesabınız onaylı değil. Lütfen destek ile iletişime geçin.',
                'code' => 403
            ];
        }

        return [
            'user' => $user,
            'token' => $token,
        ];
    }


    public function me(): mixed
    {
        return auth()->user();
    }

    public function updateProfile(Request $request): User
    {
        $user = auth()->user();

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('telefon')) {
            $user->telefon = $request->telefon;
        }

        if ($request->has('dogum_tarihi')) {
            $user->dogum_tarihi = $request->dogum_tarihi;
        }

        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }

        if ($request->has('il_id')) {
            $user->il_id = $request->il_id;
        }

        if ($request->has('ilce_id')) {
            $user->ilce_id = $request->ilce_id;
        }

        $user->save();

        return $user->load(['il', 'ilce']);
    }

    public function uploadProfilePhoto(Request $request): array
    {
        $user = auth()->user();

        // Önceki fotoğrafı sil
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Yeni fotoğrafı kaydet
        $filename = 'user_' . $user->id . '_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
        $path = $request->file('photo')->storeAs('profiles', $filename, 'public');

        $user->profile_photo_path = $path;
        $user->save();

        return [
            'message' => 'Profil fotoğrafı başarıyla yüklendi.',
            'photo_url' => asset('storage/' . $path),
            'user' => $user
        ];
    }

    public function logout(): void
    {
        auth()->logout();
    }
}
