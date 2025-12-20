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
// ------------------
// PUBLIC ROUTES
// ------------------

Route::get('/', fn() => view('welcome'));

Route::get('/home', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

// ------------------
// AUTH ROUTES
// ------------------

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ------------------
// ADMIN PANEL
// ------------------

Route::prefix('admin')->name('admin.')->group(function () {

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
