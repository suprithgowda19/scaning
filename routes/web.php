<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\{
    VenueController,
    ScreenController,
    UserController,
    StaffScreenAssignmentController,
    SchedulerController,
    SchedulerImportController
};
use App\Http\Controllers\Staff\ScanController;
use App\Http\Controllers\Dashboard\{
    StaffDashboardController,
    AdminDashboardController
};

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('redirect.logged')
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Common Auth
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/profile', [UserController::class, 'profile'])
    ->name('profile.show');

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active.user', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('users', UserController::class);
        Route::post('/users/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        Route::resource('schedulers', SchedulerController::class);
        Route::post('schedulers/import', [SchedulerImportController::class, 'import'])
            ->name('schedulers.import');

        Route::resource('venues', VenueController::class);

        Route::resource('screens', ScreenController::class);
        Route::post('/screens/toggle-status', [ScreenController::class, 'toggleStatus'])
            ->name('screens.toggle-status');

        Route::resource('staff-assignments', StaffScreenAssignmentController::class);
    });

/*
|--------------------------------------------------------------------------
| Staff Scanning (SESSION-BASED — FINAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff', 'active.user'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        // Scan dashboard
        Route::get('/scan', [ScanController::class, 'index'])
            ->name('scan.index');

        // Scan action
        Route::post('/scan', [ScanController::class, 'scan'])
            ->name('scan.store');
    });

/*
|--------------------------------------------------------------------------
| Staff Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])
    ->prefix('dashboard/staff')
    ->name('dashboard.staff.')
    ->group(function () {

        Route::get('/', [StaffDashboardController::class, 'index'])
            ->name('index');

        Route::get('/export/excel', [StaffDashboardController::class, 'exportExcel'])
            ->name('export.excel');
    });

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('dashboard/admin')
    ->name('dashboard.admin.')
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('index');

        Route::get('/export/excel', [AdminDashboardController::class, 'exportExcel'])
            ->name('export.excel');
    });
