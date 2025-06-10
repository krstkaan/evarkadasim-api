<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;

Route::get('/run-storage-link', function () {
    try {
        Artisan::call('storage:link');
        return '✅ storage:link başarılı';
    } catch (\Throwable $e) {
        return '❌ Hata: ' . $e->getMessage();
    }
});
