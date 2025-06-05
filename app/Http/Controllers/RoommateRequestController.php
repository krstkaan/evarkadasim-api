<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoommateRequest;

class RoommateRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
        ]);

        $userId = auth()->id();

        // Aynı kullanıcı aynı ilana birden fazla başvuru yapamasın
        $exists = RoommateRequest::where('listing_id', $request->listing_id)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Bu ilana zaten başvuru yaptınız.'], 409);
        }

        $requestModel = RoommateRequest::create([
            'listing_id' => $request->listing_id,
            'user_id' => $userId,
            'status' => 'pending', // pending | accepted | rejected
        ]);

        return response()->json(['message' => 'Başvuru başarıyla gönderildi.'], 201);
    }

    public function index(Request $request)
    {
        $userId = auth()->id();

        $requests = RoommateRequest::where('user_id', $userId)
            ->with('listing')
            ->get();

        return response()->json($requests);
    }

    public function incoming(Request $request)
    {
        $ownerId = auth()->id();

        $requests = RoommateRequest::with(['user', 'listing'])
            ->whereHas('listing', function ($query) use ($ownerId) {
                $query->where('user_id', $ownerId);
            })
            ->where('status', 'pending')
            ->latest()
            ->get();

        return response()->json($requests);
    }

    public function decide(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:accepted,rejected',
        ]);

        $userId = auth()->id();

        $requestModel = RoommateRequest::where('id', $id)
            ->whereHas('listing', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();

        if (!$requestModel) {
            return response()->json(['message' => 'Talep bulunamadı veya yetkiniz yok.'], 404);
        }

        // Talep durumunu güncelle
        $requestModel->status = $request->input('action');
        $requestModel->save();

        // Eğer kabul edildiyse
        if ($request->input('action') === 'accepted') {
            // Diğer tüm talepleri reddet
            RoommateRequest::where('listing_id', $requestModel->listing_id)
                ->where('id', '!=', $requestModel->id)
                ->update(['status' => 'rejected']);

            // İlanı kapat
            \App\Models\Listing::where('id', $requestModel->listing_id)
                ->update(['status' => 'closed']);
        }

        return response()->json(['message' => 'Talep güncellendi.']);
    }



    // 

}
