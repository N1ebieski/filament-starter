<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\Index\IndexController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', IndexController::class)
        ->can('viewAny', User::class)
        ->name('user.index');
});
