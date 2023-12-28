<?php

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

Route::get('/login/redirect', [\App\Http\Controllers\AuthController::class, 'redirect'])->name('redirect');
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/auth.php', [\App\Http\Controllers\AuthController::class, 'auth'])->name('auth');

Route::middleware('auth')->group(function() {
    Route::get('/', [\App\Http\Controllers\OrganizationController::class, 'home'])->name('home');
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::get('/me', [\App\Http\Controllers\OrganizationController::class, 'me'])->name('me');
    Route::get('/me/provisioning', [\App\Http\Controllers\OrganizationController::class, 'provisioning'])->name('me.prov');

    Route::middleware('can:admin')->group(function() {
        Route::get('/sync/{sync}', [\App\Http\Controllers\SyncController::class, 'show'])->name('sync');
        Route::post('/settings', [\App\Http\Controllers\OrganizationController::class, 'update']);
        Route::get('/settings/setAdmin', [\App\Http\Controllers\OrganizationController::class, 'setAdmin'])->name('settings.setAdmin');
        Route::get('/settings', [\App\Http\Controllers\OrganizationController::class, 'edit'])->name('settings');
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
        Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    });

});

Route::get('login/user/{user}', [\App\Http\Controllers\AuthController::class, 'loginAsUser']);
