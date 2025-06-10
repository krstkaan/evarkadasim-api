<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\ListingLogService;
use App\Models\User;

class ListingService
{
    protected string $mlUrl;

    public function __construct()
    {
        $this->mlUrl = env('ML_URL', 'http://127.0.0.1:8001');
    }

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

        ListingLogService::log($listing->id, 'delete', 'Kullanıcı ilanı sildi.');

        $listing->delete();
        return true;
    }

    public function getAllExceptUser($userId)
    {
        $currentUser = User::findOrFail($userId);

        if (!$currentUser->character_label) {
            return collect();
        }

        $listings = Listing::where('user_id', '!=', $userId)
            ->where('status', 'approved')
            ->with(['images', 'user'])
            ->latest()
            ->get();

        $filtered = $listings->map(function ($listing) use ($currentUser) {
            $label1 = $currentUser->character_label;
            $label2 = $listing->user->character_label;

            if (!$label2) {
                return null;
            }

            try {
                $response = Http::timeout(2)->post($this->mlUrl . '/predict-score', [
                    'label1' => $label1,
                    'label2' => $label2,
                ]);

                if ($response->failed()) {
                    Log::warning('Eşleşme skoru alınamadı', [
                        'user1' => $label1,
                        'user2' => $label2,
                        'body' => $response->body(),
                    ]);
                    return null;
                }

                $score = $response->json('score');
                if ($score < 10) {
                    return null;
                }

                $listing->match_score = $score;
                return $listing;

            } catch (\Exception $e) {
                Log::error('ML servisine bağlanılamadı (predict-score)', [
                    'label1' => $label1,
                    'label2' => $label2,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        })->filter()->values();

        return $filtered;
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

    public function closeById($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->status = 'closed';
        $listing->save();
    }
}
