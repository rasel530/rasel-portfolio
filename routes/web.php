<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'index'])->name('home');

// Training detail pages (public)
Route::get('/training/{slug}', [TrainingController::class, 'show'])->name('training.show');

// Public contact form — rate-limited to deter spam/abuse.
Route::post('/contact', [PublicController::class, 'contact'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

// Redirect default login route to admin login
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Password Reset
|--------------------------------------------------------------------------
*/

Route::get('/admin/password/reset', [AuthController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('/admin/password/email', [AuthController::class, 'sendResetLinkEmail'])
    ->middleware('throttle:5,1')
    ->name('password.email');
Route::get('/admin/password/reset/{token}', [AuthController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/admin/password/reset', [AuthController::class, 'reset'])
    ->middleware('throttle:5,1')
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
| Any authenticated administrator OR editor can manage content.
| User management and site settings are restricted to administrators
| via Gates (manage-users / manage-settings) instead of a hardcoded
| role check in middleware.
*/

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Profile (single record, only index / edit / update)
    Route::resource('profiles', ProfileController::class)->only(['index', 'edit', 'update']);

    // Content collections (admin OR editor)
    Route::resource('skills', SkillController::class);
    Route::resource('experiences', ExperienceController::class);
    Route::resource('educations', EducationController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('trainings', TrainingController::class);

    // Contact messages
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages.index');
    Route::get('/messages/{id}', [AdminController::class, 'showMessage'])->name('messages.show');
    Route::patch('/messages/{id}/read', [AdminController::class, 'markRead'])->name('messages.read');
    Route::delete('/messages/{id}', [AdminController::class, 'destroyMessage'])->name('messages.destroy');

    // Site header/footer settings (admin only)
    Route::get('/settings', [SiteSettingController::class, 'edit'])
        ->middleware('can:manage-settings')
        ->name('settings.edit');
    Route::put('/settings', [SiteSettingController::class, 'update'])
        ->middleware('can:manage-settings')
        ->name('settings.update');

    // Admin user management (admin only)
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware('can:manage-users');
});
