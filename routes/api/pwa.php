<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PWA\FilesController;

Route::name('pwa.')->prefix('pwa')->group(function () {
    Route::get('files', FilesController::class)->name('files');
});
