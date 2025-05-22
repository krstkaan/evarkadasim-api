<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Message;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function startChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_user_id' => 'required|exists:users,id',
            'listing_id' => 'nullable|exists:listings,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $room = $this->chatService->startChat(
                $request->input('target_user_id'),
                $request->input('listing_id')
            );

            return response()->json([
                'room_id' => $room->id,
                'room' => $room,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function myRooms()
    {
        $rooms = $this->chatService->getMyRooms();

        return response()->json([
            'rooms' => $rooms
        ]);
    }

    public function storeMessage(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'text' => 'required|string',
            'sent_at' => 'nullable|date',
        ]);

        $user = auth()->user();

        $message = Message::create([
            'room_id' => $validated['room_id'],
            'user_id' => $user->id,
            'text' => $validated['text'],
            'sent_at' => isset($validated['sent_at']) ? date('Y-m-d H:i:s', strtotime($validated['sent_at'])) : now(),
        ]);

        return response()->json(['message' => $message], 201);
    }
}
