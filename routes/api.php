<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterTestController;
use App\Http\Controllers\LocationController;


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
});

