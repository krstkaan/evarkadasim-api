<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Services\Admin\AuthService as AdminAuthService; // Alias verdik
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    protected AdminAuthService $adminAuthService;

    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;

        // Login ve register hariç tüm metodlar için 'auth:api' ve 'heilos.admin' middleware'lerini uygula
        // 'heilos.admin' middleware'ini birazdan oluşturacağız.
        $this->middleware(['auth:api', 'heilos.admin'], ['except' => ['login']]);
    }

    /**
     * Admin kullanıcısı için giriş yapar.
     *
     * @OA\Post(
     *     path="/api/admin/login",
     *     summary="Admin Login",
     *     tags={"Admin Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized or Invalid credentials"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->adminAuthService->login($request->only('email', 'password'));

        if (!$result) {
            // AdminAuthService 'false' döndürdüğünde ya kimlik bilgileri yanlıştır
            // ya da kullanıcı admin değildir. Her iki durumda da genel bir mesaj vermek daha güvenli olabilir.
            return response()->json(['error' => 'Giriş bilgileri geçersiz veya bu alana erişim yetkiniz yok.'], 401);
        }

        return response()->json($result);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/logout",
     *     summary="Admin Logout",
     *     tags={"Admin Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Successfully logged out"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Could not log out")
     * )
     */
    public function logout(Request $request)
    {
        if ($this->adminAuthService->logout()) {
            return response()->json(['message' => 'Başarıyla çıkış yapıldı.']);
        }
        return response()->json(['error' => 'Çıkış yapılamadı, token geçersiz olabilir.'], 500);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/me",
     *     summary="Get Authenticated Admin User",
     *     tags={"Admin Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated admin user data",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function me(Request $request)
    {
        // 'auth:api' ve 'heilos.admin' middleware'leri sayesinde buraya sadece
        // geçerli token'a sahip ve 'is_heilos'='yes' olan kullanıcılar erişebilir.
        return response()->json(auth()->user());
    }
}