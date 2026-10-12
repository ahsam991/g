<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForcedPasswordChangeController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;
use App\Http\Controllers\Auth\TwoFactorSetupController;
use App\Http\Controllers\CandidateBulkController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateImportController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public marketing site
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/destinations', [PublicController::class, 'destinations'])->name('destinations');
Route::get('/courses', [PublicController::class, 'courses'])->name('courses');
Route::get('/services', [PublicController::class, 'services'])->name('services');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
Route::post('/enquiry', [EnquiryController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('enquiry.store');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified', 'noindex'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'noindex'])->group(function () {
    // Leads
    Route::resource('leads', LeadController::class)->except(['destroy']);
    Route::post('leads/{lead}/activity', [LeadController::class, 'addActivity'])->name('leads.activity');
    Route::post('leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');
    Route::post('leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');

    // Fixed segments first: otherwise /candidates/create matches {candidate}.
    Route::post('candidates/bulk', CandidateBulkController::class)->name('candidates.bulk');

    Route::get('candidates/import', [CandidateImportController::class, 'show'])->name('candidates.import');
    Route::post('candidates/import', [CandidateImportController::class, 'store'])->name('candidates.import.store');
    Route::get('candidates/export', [CandidateImportController::class, 'export'])->name('candidates.export');

    Route::resource('candidates', CandidateController::class)->except(['show', 'destroy']);

    Route::get('candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
    Route::post('candidates/{candidate}/archive', [CandidateController::class, 'archive'])->name('candidates.archive');
    Route::post('candidates/{candidate}/restore', [CandidateController::class, 'restore'])->name('candidates.restore');

    // Documents
    Route::prefix('admin/documents')->name('admin.documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('candidates/{candidate}', [DocumentController::class, 'candidateDocuments'])->name('candidate');
        Route::get('candidates/{candidate}/upload', [DocumentController::class, 'upload'])->name('upload');
        Route::post('candidates/{candidate}/upload', [DocumentController::class, 'store'])->name('store');
        Route::post('candidates/{candidate}/request', [DocumentController::class, 'requestDocument'])->name('request');
        Route::get('{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::get('{document}/preview', [DocumentController::class, 'preview'])->name('preview');
        Route::post('{document}/verify', [DocumentController::class, 'verify'])->name('verify');
        Route::post('{document}/reject', [DocumentController::class, 'reject'])->name('reject');
        Route::post('{document}/reverse', [DocumentController::class, 'reverseRejection'])->name('reverse');
        Route::get('{document}/access-log', [DocumentController::class, 'accessLog'])->name('access-log');
        Route::get('{document}/versions', [DocumentController::class, 'versions'])->name('versions');
    });
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
        Route::resource('sliders', SliderController::class)->except(['show']);
        Route::post('sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');
    });

Route::middleware(['auth', 'verified', 'noindex', 'permission:catalogue.manage'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::resource('destinations', DestinationController::class)->except(['show']);
        Route::resource('universities', UniversityController::class)->except(['show']);
        Route::resource('services', ServiceController::class)->except(['show']);
        Route::resource('testimonials', TestimonialController::class)->except(['show']);
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
