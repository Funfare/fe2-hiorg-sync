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
    Route::get('/me/alarm/{type}', [\App\Http\Controllers\OrganizationController::class, 'alarm'])->name('me.alarm');

    Route::get('/faq', [\App\Http\Controllers\OrganizationController::class, 'faq'])->name('faq');

    Route::middleware('can:admin')->group(function() {
        Route::get('/sync/{sync}', [\App\Http\Controllers\SyncController::class, 'show'])->name('sync');
        Route::post('/settings', [\App\Http\Controllers\OrganizationController::class, 'update']);
        Route::get('/settings/setAdmin', [\App\Http\Controllers\OrganizationController::class, 'setAdmin'])->name('settings.setAdmin');
        Route::get('/settings/sync', [\App\Http\Controllers\OrganizationController::class, 'sync'])->name('settings.sync');
        Route::get('/settings', [\App\Http\Controllers\OrganizationController::class, 'edit'])->name('settings');
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
        Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');
        Route::get('/rules/preview', [\App\Http\Controllers\RuleController::class, 'preview'])->name('rules.preview');
        Route::get('/rules/{tab?}', [\App\Http\Controllers\RuleController::class, 'show'])->name('rules.show');
        Route::get('/test', [\App\Http\Controllers\RuleController::class, 'test'])->name('rules.test');
        Route::get('login/user/{user}', [\App\Http\Controllers\AuthController::class, 'loginAsUser'])->name('impersonate.user');

    });
    Route::get('/login/back', [\App\Http\Controllers\AuthController::class, 'loginBack'])->name('impersonate.back');

});

