<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    public function startChat(int $targetUserId, ?int $listingId = null): Room
    {
        $userId = Auth::id();

        if ($userId === $targetUserId) {
            throw new \Exception('Kendi kendinle sohbet başlatamazsın.');
        }

        [$user1, $user2] = $userId < $targetUserId
            ? [$userId, $targetUserId]
            : [$targetUserId, $userId];

        $room = Room::where('user_1_id', $user1)
            ->where('user_2_id', $user2)
            ->where('listing_id', $listingId)
            ->first();

        if (!$room) {
            $room = Room::create([
                'user_1_id' => $user1,
                'user_2_id' => $user2,
                'listing_id' => $listingId,
            ]);
        }

        return $room;
    }

    public function getMyRooms(): \Illuminate\Support\Collection
    {
        $userId = auth()->id();

        return Room::with(['user1', 'user2', 'listing'])
            ->where(function ($q) use ($userId) {
                $q->where('user_1_id', $userId)
                    ->orWhere('user_2_id', $userId);
            })
            ->latest()
            ->get();
    }
}
