<?php

use App\Http\Controllers\Api\User\Index\IndexController;
use App\Models\User\User;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', IndexController::class)
        ->can('viewAny', User::class)
        ->name('user.index');
});
