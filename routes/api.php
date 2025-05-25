<?php

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/character-test-questions', [CharacterTestController::class, 'index']);
    Route::post('/character-test-question', [CharacterTestController::class, 'store']);
    Route::post('/character-test-submit', [CharacterTestController::class, 'submit']);
    Route::post('upload-profile-photo', [AuthController::class, 'uploadProfilePhoto']);
    Route::get('/cities', [LocationController::class, 'cities']);
    Route::get('/cities/{cityId}', [LocationController::class, 'districts']);

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
});

Route::prefix('favorites')->middleware(['auth:api'])->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::post('/toggle', [FavoriteController::class, 'toggle']);
    Route::get('/check', [FavoriteController::class, 'check']);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');

    Route::middleware(['auth:api', 'heilos.admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/me', [AdminAuthController::class, 'me'])->name('me');
        Route::post('/update-profile', [AdminAuthController::class, 'updateProfile'])->name('updateProfile');

        // Kullanıcı yönetimi
        Route::apiResource('/users', UserController::class);

        // Admin ve normal kullanıcı ayrımı
        Route::get('/admins', [UserController::class, 'admins'])->name('admins');
        Route::get('/regular-users', [UserController::class, 'regularUsers'])->name('regularUsers');
        Route::get('/total-users', [UserController::class, 'totalUsers'])->name('totalUsers');


        // İlan yönetimi
        Route::get('/listings/count', [AdminListingController::class, 'count'])->name('listings.count'); // İsimlendirme de yapabilirsiniz

        Route::get('/listings', [AdminListingController::class, 'index'])->name('listings.index');
        Route::post('/listings/{id}/approve', [AdminListingController::class, 'approve'])->name('listings.approve');
        Route::post('/listings/{id}/reject', [AdminListingController::class, 'reject'])->name('listings.reject');
        Route::get('/listings/{id}', [AdminListingController::class, 'show'])->name('listings.show');
        Route::delete('/listings/{id}', [AdminListingController::class, 'destroy'])->name('listings.destroy');
    });
});