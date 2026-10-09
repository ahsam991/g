<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForcedPasswordChangeController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;
use App\Http\Controllers\Auth\TwoFactorSetupController;
use App\Http\Controllers\CandidateBulkController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateImportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified', 'noindex'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'noindex'])->group(function () {
    // Fixed segments first: otherwise /candidates/create matches {candidate}.
    Route::post('candidates/bulk', CandidateBulkController::class)->name('candidates.bulk');

    Route::get('candidates/import', [CandidateImportController::class, 'show'])->name('candidates.import');
    Route::post('candidates/import', [CandidateImportController::class, 'store'])->name('candidates.import.store');
    Route::get('candidates/export', [CandidateImportController::class, 'export'])->name('candidates.export');

    Route::resource('candidates', CandidateController::class)->except(['show', 'destroy']);

    Route::get('candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
    Route::post('candidates/{candidate}/archive', [CandidateController::class, 'archive'])->name('candidates.archive');
    Route::post('candidates/{candidate}/restore', [CandidateController::class, 'restore'])->name('candidates.restore');
});

// Every signed-in area stays out of search engines.
Route::middleware(['auth', 'verified', 'noindex', 'permission:users.manage'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::resource('branches', BranchController::class)->except(['show', 'destroy']);
    });

Route::middleware(['auth', 'verified', 'noindex', 'permission:settings.manage'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

Route::middleware(['auth', 'verified', 'noindex', 'permission:audit.view'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('audit', [AuditLogController::class, 'index'])->name('audit.index');
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
