<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\{
    VenueController,
    ScreenController,
    UserController,
    StaffScreenAssignmentController,
    SchedulerController,
    SchedulerImportController,
    MovieController
};
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Staff\ScanController;
use App\Http\Controllers\Reports\{
    StaffReportController,
    AdminReportController
};

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('redirect.logged')
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')
    ->get('/profile', [UserController::class, 'profile'])
    ->name('profile.show');


Route::middleware(['auth', 'active.user', 'role:admin'])
    ->prefix('dashboard/admin')
    ->name('dashboard.admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('index');
        Route::get('/poll', [AdminDashboardController::class, 'poll'])->name('poll');
    });


Route::middleware(['auth', 'active.user', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('users', UserController::class)->except(['show']);
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
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/movies',               [MovieController::class, 'index'])->name('movies.index');
        Route::get('/movies/create',        [MovieController::class, 'create'])->name('movies.create');
        Route::post('/movies',              [MovieController::class, 'store'])->name('movies.store');
        Route::get('/movies/{movie}/edit',  [MovieController::class, 'edit'])->name('movies.edit');
        Route::put('/movies/{movie}',       [MovieController::class, 'update'])->name('movies.update');

        Route::post('/movies/import',       [MovieController::class, 'import'])->name('movies.import');
    });


Route::middleware(['auth', 'active.user'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('users.show');
    });


Route::middleware(['auth', 'role:staff', 'active.user'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/scan', [ScanController::class, 'index'])
            ->name('scan.index');

        Route::post('/scan', [ScanController::class, 'scan'])
            ->name('scan.store');
    });


Route::middleware(['auth', 'role:staff'])
    ->prefix('reports/staff')
    ->name('reports.staff.')
    ->group(function () {

        Route::get('/', [StaffReportController::class, 'index'])
            ->name('index');

        Route::get('/ajax/filter', [StaffReportController::class, 'ajaxFilter'])
            ->name('ajax.filter');

        Route::get('/export/excel', [StaffReportController::class, 'exportExcel'])
            ->name('export.excel');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('reports/admin')
    ->name('reports.admin.')
    ->group(function () {

        Route::get('/', [AdminReportController::class, 'index'])
            ->name('index');

        Route::get('/ajax/filter', [AdminReportController::class, 'ajaxFilter'])
            ->name('ajax.filter');

        Route::get('/screen-slots', [AdminReportController::class, 'screenSlots'])
            ->name('screen.slots');

        Route::get('/export', [AdminReportController::class, 'exportExcel'])
            ->name('export.excel');
    });



use App\Http\Controllers\DelegateRegistrationController;
use App\Http\Controllers\DelegatePaymentController;


Route::get('/delegate/register', [DelegateRegistrationController::class, 'create'])->name('delegate.register.form');
Route::post('/delegate/register', [DelegateRegistrationController::class, 'store'])->name('delegate.store');


Route::get('/delegate/{delegate}/payment/initiate', [DelegatePaymentController::class, 'initiate'])->name('payment.start');

Route::get('/delegate/{delegate}/payment', [DelegatePaymentController::class, 'paymentPage'])->name('delegate.payment.page');
Route::post('/delegate/{delegate}/payment/order', [DelegatePaymentController::class, 'createOrder'])->name('delegate.payment.order');
Route::post('/delegate/{delegate}/payment/verify', [DelegatePaymentController::class, 'verify'])->name('delegate.payment.verify');
