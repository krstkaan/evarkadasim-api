<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\UserLogService;

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

        auth()->login($user); // ✨ Bu satır eklenirse auth()->id() artık çalışır
        $token = JWTAuth::fromUser($user);

        UserLogService::log($request, 'register', 'Yeni kullanıcı kaydı yapıldı.');

        return ['user' => $user, 'token' => $token];
    }

    public function login(array $credentials): array|bool
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return false;
        }

        $user = auth()->user();

        if (!$user->onayli) {
            auth()->logout();
            return [
                'error' => 'Hesabınız onaylı değil. Lütfen destek ile iletişime geçin.',
                'code' => 403
            ];
        }

        // ✅ Log ekle
        UserLogService::log(request(), 'login', 'Kullanıcı giriş yaptı.');

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

        // ✅ Log ekle
        UserLogService::log($request, 'update_profile', 'Kullanıcı profili güncellendi.');

        return $user->load(['il', 'ilce']);
    }

    public function uploadProfilePhoto(Request $request): array
    {
        $user = auth()->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $filename = 'user_' . $user->id . '_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
        $path = $request->file('photo')->storeAs('profiles', $filename, 'public');

        $user->profile_photo_path = $path;
        $user->save();

        // ✅ Log ekle
        UserLogService::log($request, 'upload_profile_photo', 'Kullanıcı profil fotoğrafı güncelledi.');

        return [
            'message' => 'Profil fotoğrafı başarıyla yüklendi.',
            'photo_url' => asset('storage/' . $path),
            'user' => $user
        ];
    }

    public function logout(): void
    {
        UserLogService::log(request(), 'logout', 'Kullanıcı çıkış yaptı.');
        auth()->logout();
    }
}
