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

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'redirect'])->name('login');
Route::get('/auth.php', [\App\Http\Controllers\AuthController::class, 'auth']);

Route::middleware('auth')->group(function() {
    Route::get('/', [\App\Http\Controllers\OrganizationController::class, 'home'])->name('home');
    Route::get('/settings', [\App\Http\Controllers\OrganizationController::class, 'edit'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\OrganizationController::class, 'update']);
});
