<?php

use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterTestController;
use App\Http\Controllers\Dropdown\AgeRangeController;
use App\Http\Controllers\Dropdown\BuildingAgeController;
use App\Http\Controllers\Dropdown\FurnitureStatusController;
use App\Http\Controllers\Dropdown\HeatingTypeController;
use App\Http\Controllers\Dropdown\HouseTypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Dropdown\RoommateGenderController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\UserController; // Yeni eklenen
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\RoommateRequestController;
use App\Http\Controllers\UserRatingController;
use App\Http\Controllers\MatchFeedbackController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Route::get('/migrate', function () {
//     try {
//         Artisan::call('migrate', ['--force' => true]);
//         return Response::json([
//             'message' => 'Migration başarılı',
//             'output' => Artisan::output()
//         ], 200);
//     } catch (\Throwable $e) {
//         return Response::json([
//             'error' => $e->getMessage()
//         ], 500);
//     }
// });

// Route::get('/run-ililce-seed', function () {
//     try {
//         Artisan::call('db:seed', [
//             '--class' => 'IlIlceMahalleImportSeeder',
//             '--force' => true,
//         ]);

//         return response()->json([
//             'status' => '✅ Seeder başarıyla çalıştı',
//             'output' => Artisan::output()
//         ]);
//     } catch (\Throwable $e) {
//         return response()->json([
//             'status' => '❌ Hata oluştu',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// });

// Route::get('/run-storage-link', function () {
//     try {
//         Artisan::call('storage:link');

//         return response()->json([
//             'status' => '✅ storage:link başarılı',
//             'output' => Artisan::output(),
//         ]);
//     } catch (\Throwable $e) {
//         return response()->json([
//             'status' => '❌ Hata oluştu',
//             'error' => $e->getMessage(),
//         ], 500);
//     }
// });

// Route::get('/run-question-seed', function () {
//     try {
//         Artisan::call('db:seed', [
//             '--class' => 'QuestionAndOptionSeeder',
//             '--force' => true,
//         ]);

//         return response()->json([
//             'status' => '✅ Seeder başarıyla çalıştı',
//             'output' => Artisan::output()
//         ]);
//     } catch (\Throwable $e) {
//         return response()->json([
//             'status' => '❌ Hata oluştu',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// });

Route::get('/run-lookup-seed', function () {
    try {
        Artisan::call('db:seed', [
            '--class' => 'LookupTablesSeeder',
            '--force' => true,
        ]);

        return response()->json([
            'status' => '✅ Lookup seeder başarıyla çalıştı',
            'output' => Artisan::output()
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => '❌ Hata oluştu',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/character-test-questions', [CharacterTestController::class, 'index']);
    Route::post('/character-test-question', [CharacterTestController::class, 'store']);
    Route::post('/character-test-submit', [CharacterTestController::class, 'submit']);
    Route::post('/match-score', [RecommendationController::class, 'matchScore']);
    Route::post('upload-profile-photo', [AuthController::class, 'uploadProfilePhoto']);
    Route::get('/cities', [LocationController::class, 'cities']);
    Route::get('/cities/{cityId}', [LocationController::class, 'districts']);
    Route::post('/match-feedbacks', [MatchFeedbackController::class, 'store']);


    Route::prefix('dropdowns')->group(function () {
        Route::apiResource('roommate-genders', RoommateGenderController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
        Route::apiResource('age-ranges', AgeRangeController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
        Route::apiResource('house-types', HouseTypeController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
        Route::apiResource('furniture-statuses', FurnitureStatusController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
        Route::apiResource('heating-types', HeatingTypeController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
        Route::apiResource('building-ages', BuildingAgeController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
    });

    Route::prefix('listings')->group(function () {
        Route::post('/', [ListingController::class, 'store']);
        Route::get('/', [ListingController::class, 'index']);
        Route::get('/me', [ListingController::class, 'myListing']);
        Route::get('/{id}', [ListingController::class, 'show']);
        Route::delete('/{id}', [ListingController::class, 'destroy']);
    });

    Route::prefix('chat')->group(function () {
        Route::post('/start', [ChatController::class, 'startChat']);
        Route::get('/my-rooms', [ChatController::class, 'myRooms']);
        Route::post('/messages', [ChatController::class, 'storeMessage']);
    });

    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::post('/toggle', [FavoriteController::class, 'toggle']);
        Route::get('/check', [FavoriteController::class, 'check']);
    });

    Route::prefix('user-ratings')->group(function () {
        Route::post('/', [UserRatingController::class, 'store']);
        Route::get('/{userId}', [UserRatingController::class, 'index']);
    });

    Route::prefix('roommate-requests')->group(function () {
        Route::post('/', [RoommateRequestController::class, 'store']);
        Route::get('/', [RoommateRequestController::class, 'index']);
        Route::get('/incoming', [RoommateRequestController::class, 'incoming']);
        Route::post('/{id}/decide', [RoommateRequestController::class, 'decide']);
    });

});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');

    Route::middleware(['auth:api', 'heilos.admin'])->group(function () {

        // Admin oturum işlemleri
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/me', [AdminAuthController::class, 'me'])->name('me');
        Route::post('/update-profile', [AdminAuthController::class, 'updateProfile'])->name('updateProfile');

        // Admin kullanıcı işlemleri

        Route::prefix('users')->group(function () {
            Route::get('/admins', [UserController::class, 'admins'])->name('users.admins');
            Route::get('/regular-users', [UserController::class, 'regularUsers'])->name('users.regularUsers');
            Route::get('/total-users', [UserController::class, 'totalUsers'])->name('users.totalUsers');
            Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
            Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
        });

        // Admin listeleme işlemleri

        Route::prefix('listings')->group(function () {
            Route::get('/pending', [AdminListingController::class, 'pending'])->name('listings.pending');
            Route::get('/approved', [AdminListingController::class, 'approved'])->name('listings.approved');
            Route::get('/rejected', [AdminListingController::class, 'rejected'])->name('listings.rejected');
            Route::get('/', [AdminListingController::class, 'index'])->name('listings.index');
            Route::get('/total-listings', [AdminListingController::class, 'totalListings'])->name('listings.totalListings');
            Route::get('/{id}', [AdminListingController::class, 'show'])->name('listings.show');
            Route::post('/{id}/approve', [AdminListingController::class, 'approve'])->name('listings.approve');
            Route::post('/{id}/reject', [AdminListingController::class, 'reject'])->name('listings.reject');
            Route::delete('/{id}', [AdminListingController::class, 'destroy'])->name('listings.destroy');
        });
        Route::get('/logs', [LogController::class, 'index']);


    });
});