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
        $data['status'] = 'pending'; // ✅ Yeni ilanlar admin onayını bekleyecek

        $listing = Listing::create($data);

        // Kullanıcının ilan referansını güncelle
        $user = \App\Models\User::findOrFail($userId);
        $user->update(['listing_id' => $listing->id]);

        // Görselleri kaydet
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
        return Listing::where('user_id', $userId)
            ->with('images')
            ->first();
    }

    public function delete($id, $userId)
    {
        $listing = Listing::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $user = \App\Models\User::findOrFail($userId);
        if ($user->listing_id === $listing->id) {
            $user->update(['listing_id' => null]);
        }

        $listing->delete();
        return true;
    }

    public function getAllExceptUser($userId)
    {
        return Listing::where('user_id', '!=', $userId)
            ->where('status', 'approved') // ✅ Sadece yayınlanmış ilanlar
            ->with(['images'])
            ->latest()
            ->get();
    }

    // Admin için bekleyen ilanları getir
    public function getPendingListings()
    {
        return Listing::where('status', 'pending')
            ->with(['images', 'user'])
            ->latest()
            ->get();
    }

    // Admin onayı
    public function approve($listingId)
    {
        $listing = Listing::findOrFail($listingId);
        $listing->update(['status' => 'approved']);
    }

    public function reject($listingId)
    {
        $listing = Listing::findOrFail($listingId);
        $listing->update(['status' => 'rejected']);
    }
    public function getById($id)
    {
        return Listing::with(['images', 'user'])->findOrFail($id);
    }
}
