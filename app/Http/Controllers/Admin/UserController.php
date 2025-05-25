<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'heilos.admin']);
    }

    /**
     * Tüm kullanıcıları listele
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Arama filtresi
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // İzin verilen sıralama alanları
        $allowedSorts = ['name', 'email', 'created_at', 'is_helios', 'onayli', 'character_test_done'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Sayfalama
        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage);

        return response()->json($users);
    }

    /**
     * Sadece admin kullanıcıları listele
     */
    public function admins(Request $request)
    {
        $query = User::where('is_helios', 1);

        // Arama filtresi
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['name', 'email', 'created_at', 'onayli', 'character_test_done'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 15);
        $admins = $query->paginate($perPage);

        return response()->json($admins);
    }

    /**
     * Normal kullanıcıları listele (admin olmayanlar)
     */
    public function regularUsers(Request $request)
    {
        $query = User::where('is_helios', 0);

        // Arama filtresi
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['name', 'email', 'created_at', 'onayli', 'character_test_done'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage);

        return response()->json($users);
    }

    /**
     * Kullanıcı detayını göster
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Kullanıcıyı güncelle
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'is_helios' => 'sometimes|boolean',
            'onayli' => 'sometimes|boolean',
        ]);

        $user->update($request->only(['name', 'email', 'is_helios', 'onayli']));

        return response()->json([
            'message' => 'Kullanıcı başarıyla güncellendi',
            'user' => $user
        ]);
    }

    /**
     * Kullanıcıyı sil
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Kendi hesabını silmeyi engelle
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Kendi hesabınızı silemezsiniz'], 400);
        }

        $user->delete();

        return response()->json(['message' => 'Kullanıcı başarıyla silindi']);
    }

    /**
     * Toplam kullanıcı sayısını döndür
     */

    public function totalUsers()
    {
        $totalUsers = User::count();
        return response()->json(['total' => $totalUsers]);
    }
}