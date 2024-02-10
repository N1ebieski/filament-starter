<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('proba', function (User $user) {
    dd($user->permissions()->wherePivot('buba', 'bambo')->orWherePivot('buba', 'dsadsad')->toSql());

    return view('welcome');
});
