<?php
namespace App\Services;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    public function toggle($listingId): bool
    {
        $userId = Auth::id();

        $favorite = Favorite::where('user_id', $userId)
            ->where('listing_id', $listingId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return false;
        } else {
            Favorite::create([
                'user_id' => $userId,
                'listing_id' => $listingId
            ]);
            return true;
        }
    }

    public function isFavorited($listingId): bool
    {
        return Favorite::where('user_id', Auth::id())
            ->where('listing_id', $listingId)
            ->exists();
    }
}
