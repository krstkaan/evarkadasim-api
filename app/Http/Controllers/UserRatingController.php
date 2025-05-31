<?php

namespace App\Http\Controllers;

use App\Models\UserRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:0|max:100',
            'comment' => 'nullable|string',
        ]);

        $rating = UserRating::create([
            'rater_user_id' => Auth::id(),
            'target_user_id' => $request->target_user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Değerlendirme kaydedildi.', 'data' => $rating]);
    }

    public function index(Request $request, $userId)
    {
        // Belirli bir kullanıcıya gelen tüm puanları getir
        $ratings = UserRating::where('target_user_id', $userId)->get();

        return response()->json($ratings);
    }
}
