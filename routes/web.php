<?php

use App\Http\Controllers\Auth\ForcedPasswordChangeController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;
use App\Http\Controllers\Auth\TwoFactorSetupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/password/change', [ForcedPasswordChangeController::class, 'show'])->name('password.change');
    Route::post('/password/change', [ForcedPasswordChangeController::class, 'store'])->name('password.change.store');

    Route::get('/two-factor', [TwoFactorSetupController::class, 'show'])->name('two-factor.show');
    Route::post('/two-factor', [TwoFactorSetupController::class, 'store'])->name('two-factor.store');
    Route::delete('/two-factor', [TwoFactorSetupController::class, 'destroy'])->name('two-factor.destroy');
});

Route::get('/two-factor/challenge', [TwoFactorChallengeController::class, 'show'])->name('two-factor.challenge');
Route::post('/two-factor/challenge', [TwoFactorChallengeController::class, 'store'])->name('two-factor.challenge.store');

require __DIR__.'/auth.php';
