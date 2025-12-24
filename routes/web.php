<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ScreenController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\SlotController;
use App\Http\Controllers\Admin\ScreenSlotAssignmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Staff\ScanController;
use App\Http\Controllers\Admin\StaffScreenAssignmentController;
use App\Http\Controllers\Dashboard\StaffDashboardController;
// ------------------
// PUBLIC ROUTES
// ------------------

Route::get('/', function () {
    return redirect()->route('login');
});


// ------------------
// AUTH ROUTES
// ------------------

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('redirect.logged')
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);


// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ------------------
// ADMIN PANEL
// ------------------
Route::middleware(['auth'])
    ->get('/profile', [UserController::class, 'profile'])
    ->name('profile.show');

Route::middleware(['auth', 'active.user'])->prefix('admin')->name('admin.')->group(function () {

    Route::resource('users', UserController::class);
    Route::post('users/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');



    // ------------------
    // Venues
    // ------------------
    Route::resource('venues', VenueController::class);

    // ------------------
    // Screens
    // ------------------
    Route::post('/screens/toggle-status', [ScreenController::class, 'toggleStatus'])
        ->name('screens.toggle-status');

    Route::resource('screens', ScreenController::class);

    // ------------------
    // Movies
    // ------------------
    Route::post('/movies/toggle-status', [MovieController::class, 'toggleStatus'])
        ->name('movies.toggle-status');

    Route::resource('movies', MovieController::class);
    Route::resource('ssa', ScreenSlotAssignmentController::class)->names('ssa');


    Route::resource('slots', SlotController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/staff/scan', [ScanController::class, 'index'])->name('staff.scan.index');
    Route::post('/staff/scan', [ScanController::class, 'scan'])->name('staff.scan.store');
});
Route::middleware(['auth', 'active.user', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource(
            'staff-assignments',
            StaffScreenAssignmentController::class
        );
    });

Route::middleware(['auth'])->prefix('staff')->group(function () {
    Route::get('/scan', [ScanController::class, 'index'])->name('staff.scan.index');
    Route::post('/scan', [ScanController::class, 'scan'])->name('staff.scan.store');
    Route::get('/scan/stats', [ScanController::class, 'stats'])->name('staff.scan.stats');
});

Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->name('staff.dashboard');

    Route::get('/staff/dashboard/export/excel', [StaffDashboardController::class, 'exportExcel'])
        ->name('staff.dashboard.export.excel');

    Route::get('/staff/dashboard/export/pdf', [StaffDashboardController::class, 'exportPdf'])
        ->name('staff.dashboard.export.pdf');
});
