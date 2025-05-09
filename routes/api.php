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
        Route::apiResource('roommate-genders', RoommateGenderController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::apiResource('age-ranges', AgeRangeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::apiResource('house-types', HouseTypeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::apiResource('furniture-statuses', FurnitureStatusController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::apiResource('heating-types', HeatingTypeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::apiResource('building-ages', BuildingAgeController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    Route::prefix('listings')->group(function () {
        Route::post('/', [\App\Http\Controllers\ListingController::class, 'store']);
        Route::get('/', [\App\Http\Controllers\ListingController::class, 'index']);

        Route::get('/me', [\App\Http\Controllers\ListingController::class, 'myListing']);
        Route::delete('/{id}', [\App\Http\Controllers\ListingController::class, 'destroy']);
    });
});

