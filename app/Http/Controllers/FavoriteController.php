<?php

namespace App\Http\Controllers;

use App\Services\FavoriteService;
use Illuminate\Http\Request;


class FavoriteController extends Controller
{
    protected $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $user = auth()->user();

        $favorites = $user->favorites()->with('listing.images')->latest()->get();

        return response()->json([
            'favorites' => $favorites->map(function ($fav) {
                return [
                    'id' => $fav->listing->id,
                    'title' => $fav->listing->title,
                    'rent_price' => $fav->listing->rent_price,
                    'square_meters' => $fav->listing->square_meters,
                    'images' => $fav->listing->images->map(fn($img) => asset('storage/' . $img->image_path)),
                ];
            }),
        ]);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
        ]);

        $favorited = $this->service->toggle($request->listing_id);

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
        ]);

        $isFavorited = $this->service->isFavorited($request->listing_id);

        return response()->json([
            'favorited' => $isFavorited,
        ]);
    }
}

