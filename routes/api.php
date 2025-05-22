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
        Route::post('/messages', [ChatController::class, 'storeMessage']); // ✅ bu satır eklenecek

    });
});

Route::prefix('helios')->middleware(['auth:api', 'is_helios'])->group(function () {
    Route::get('/pending-listings', [ListingController::class, 'pending']);
    Route::post('/approve-listing/{id}', [ListingController::class, 'approve']);
    Route::post('/reject-listing/{id}', [ListingController::class, 'reject']);
});

Route::prefix('favorites')->middleware(['auth:api'])->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::post('/toggle', [FavoriteController::class, 'toggle']);
    Route::get('/check', [FavoriteController::class, 'check']);
});

