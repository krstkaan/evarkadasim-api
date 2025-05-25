<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Support\Facades\Storage;
use App\Services\ListingLogService;
use App\Models\User;

class ListingService
{
    public function create(array $data, $images, $userId)
    {
        if (Listing::where('user_id', $userId)->exists()) {
            throw new \Exception("Zaten bir ilanınız var.");
        }

        $data['user_id'] = $userId;
        $data['status'] = 'pending';

        $listing = Listing::create($data);

        $user = User::findOrFail($userId);
        $user->update(['listing_id' => $listing->id]);

        foreach ($images as $image) {
            $path = $image->store('uploads/listings', 'public');

            ListingImage::create([
                'listing_id' => $listing->id,
                'image_path' => $path,
            ]);
        }

        // ✅ Log oluştur
        ListingLogService::log($listing->id, 'create', 'Kullanıcı yeni ilan oluşturdu.');

        return $listing->load('images');
    }

    public function getByUser($userId)
    {
        return Listing::where('user_id', $userId)
            ->with('images')
            ->get();
    }

    public function delete($id, $userId)
    {
        $listing = Listing::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $user = User::findOrFail($userId);
        if ($user->listing_id === $listing->id) {
            $user->update(['listing_id' => null]);
        }

        // ✅ Log oluştur (önce log, sonra silme)
        ListingLogService::log($listing->id, 'delete', 'Kullanıcı ilanı sildi.');

        $listing->delete();
        return true;
    }

    public function getAllExceptUser($userId)
    {
        return Listing::where('user_id', '!=', $userId)
            ->where('status', 'approved')
            ->with(['images'])
            ->latest()
            ->get();
    }

    public function getPendingListings()
    {
        return Listing::where('status', 'pending')
            ->with(['images', 'user'])
            ->latest()
            ->get();
    }

    public function getById($id)
    {
        return Listing::with(['images', 'user'])->findOrFail($id);
    }
}
