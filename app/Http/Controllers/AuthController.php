<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RoommateRequest;
use Auth;
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
        $user = Auth::user();

        // 1. Kullanıcının sahibi olduğu ilanlara gelen accepted roommate request var mı?
        $ownerMatch = RoommateRequest::whereHas('listing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'accepted')->with(['user', 'listing'])->first();

        if ($ownerMatch) {
            $user->roommate = [
                'id' => $ownerMatch->user->id,
                'full_name' => $ownerMatch->user->name,
                'started_at' => $ownerMatch->updated_at->toDateString(),
                'listing_id' => $ownerMatch->listing->id, // ✅ Eklendi
                'profile_photo_url' => $ownerMatch->user->profile_photo_url, // ✅ Bonus: Profil fotoğrafı
            ];
            return $user;
        }

        // 2. Kullanıcı başvuru yapmış ve kabul edilmiş mi?
        $requesterMatch = RoommateRequest::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->with('listing.user')
            ->first();

        if ($requesterMatch) {
            $owner = $requesterMatch->listing->user;
            $user->roommate = [
                'id' => $owner->id,
                'full_name' => $owner->name,
                'started_at' => $requesterMatch->updated_at->toDateString(),
                'listing_id' => $requesterMatch->listing->id, // ✅ Eklendi
                'profile_photo_url' => $owner->profile_photo_url, // ✅ Bonus: Profil fotoğrafı
            ];
            return $user;
        }

        // 3. Eşleşme yoksa null dön
        $user->roommate = null;
        return $user;
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
