<?php

namespace App\Http\Controllers\Admin;

use App\Models\Listing;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function __construct()
    {
        // Sadece 'count' metodu için middleware'ları atla
        $this->middleware(['auth:api', 'heilos.admin'])->except(['count']);
    }

    /**
     * Tüm ilanları listele
     */
    public function index(Request $request)
    {
        $query = Listing::query();

        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sıralama
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['title', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Sayfalama
        $perPage = $request->get('per_page', 15);
        $listings = $query->paginate($perPage);

        return response()->json($listings);
    }

    /**
     * Belirli bir ilanı göster
     */
    public function show($id)
    {
        $listing = Listing::with(['images', 'user'])->findOrFail($id);
        return response()->json($listing);
    }

    /**
     * İlanı onayla
     */
    public function approve($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->status = 'approved';
        $listing->save();

        return response()->json(['message' => 'İlan başarıyla onaylandı.']);
    }

    /**
     * İlanı reddet
     */
    public function reject($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->status = 'rejected';
        $listing->save();

        return response()->json(['message' => 'İlan reddedildi.']);
    }

    /**
     * İlanı sil (soft delete)
     */
    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->delete();

        return response()->json(['message' => 'İlan başarıyla silindi.']);
    }

    /**
     * Toplam ilan sayısını döndür
     */
    public function totalListings()
    {
        $totalListings = Listing::count();
        return response()->json(['total' => $totalListings]);
    }

    /**
     * Bekleyen ilanları listele
     */
    public function pending(Request $request)
    {
        $query = Listing::where('status', 'pending');

        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sıralama
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['title', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Sayfalama
        $perPage = $request->get('per_page', 15);
        $listings = $query->paginate($perPage);

        return response()->json($listings);
    }
    /**
     * Onaylanmış ilanları listele
     */
    public function approved(Request $request)
    {
        $query = Listing::where('status', 'approved');

        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sıralama
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['title', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Sayfalama
        $perPage = $request->get('per_page', 15);
        $listings = $query->paginate($perPage);

        return response()->json($listings);
    }
    /**
     * Reddedilen ilanları listele
     */
    public function rejected(Request $request)
    {
        $query = Listing::where('status', 'rejected');

        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sıralama
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['title', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Sayfalama
        $perPage = $request->get('per_page', 15);
        $listings = $query->paginate($perPage);

        return response()->json($listings);
    }
}
