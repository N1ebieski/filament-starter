<?php

use App\Http\Controllers\Api\PWA\Files\FilesController;
use Illuminate\Support\Facades\Route;

Route::name('pwa.')->prefix('pwa')->group(function (): void {
    Route::get('files', FilesController::class)->name('files');
});
