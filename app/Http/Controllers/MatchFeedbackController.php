<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MatchFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MatchFeedbackController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Debug için log ekleyin
            Log::info('Feedback request received:', $request->all());
            
            $data = $request->validate([
                'to_user_id' => 'required|exists:users,id',
                'listing_id' => 'required|exists:listings,id',
                'roommate_request_id' => 'nullable|exists:roommate_requests,id',
                'communication_score' => 'required|integer|min:1|max:5',
                'sharing_score' => 'required|integer|min:1|max:5',
                'overall_score' => 'required|integer|min:1|max:10',
                'would_live_again' => 'required|boolean',
                'comment' => 'nullable|string',
            ]);

            $data['from_user_id'] = Auth::id();
            
            Log::info('Validated data:', $data);

            // Çift kayıt engelleme
            $exists = MatchFeedback::where('from_user_id', $data['from_user_id'])
                ->where('to_user_id', $data['to_user_id'])
                ->where('listing_id', $data['listing_id'])
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($exists) {
                Log::warning('Duplicate feedback attempt', $data);
                return response()->json(['message' => 'Bugün zaten değerlendirme yaptınız.'], 409);
            }

            // Feedback kaydı
            $feedback = MatchFeedback::create($data);
            Log::info('Feedback created successfully:', $feedback->toArray());

            // Kullanıcının feedback tarihini güncelle
            $user = Auth::user();
            $user->last_feedback_at = now();
            $user->save();

            return response()->json(['message' => 'Geri bildirim kaydedildi.', 'data' => $feedback], 201);
            
        } catch (\Exception $e) {
            Log::error('Feedback creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'message' => 'Geri bildirim kaydedilemedi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}