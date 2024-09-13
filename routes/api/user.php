<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\Index\IndexController;

Route::get('users', IndexController::class)->name('user.index');
