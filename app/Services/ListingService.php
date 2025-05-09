<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Support\Facades\Storage;

class ListingService
{
    public function create(array $data, $images, $userId)
    {
        // Eğer bu kullanıcıya ait aktif ilan varsa, yenisini oluşturma
        if (Listing::where('user_id', $userId)->exists()) {
            throw new \Exception("Zaten bir ilanınız var.");
        }

        $data['user_id'] = $userId;
        $listing = Listing::create($data);

        foreach ($images as $image) {
            $path = $image->store('uploads/listings', 'public');

            ListingImage::create([
                'listing_id' => $listing->id,
                'image_path' => $path,
            ]);
        }

        return $listing->load('images');
    }

    public function getByUser($userId)
    {
        return Listing::where('user_id', $userId)->with('images')->first();
    }

    public function delete($id, $userId)
    {
        $listing = Listing::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $listing->delete();
        return true;
    }
}
