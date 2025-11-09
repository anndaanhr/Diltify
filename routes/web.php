<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\PlaylistController;
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

// Public Routes
Route::get('/', function () {
    if (session()->has('user_id')) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/music/ajax-search', [App\Http\Controllers\MusicController::class, 'ajaxSearch'])
       ->name('music.ajax.search');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth.custom')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Music routes
    Route::get('/music/search', [MusicController::class, 'index'])->name('music.search');
    Route::post('/music/search', [MusicController::class, 'search']);
    Route::get('/music/track/{trackId}', [MusicController::class, 'show'])->name('music.track.show');
    Route::post('/music/add-to-favorite', [MusicController::class, 'addToFavorite'])->name('music.add-to-favorite');
    
    // Dashboard routes
    Route::get('/dashboard/leaderboard', [DashboardController::class, 'getLeaderboardPage'])->name('dashboard.leaderboard');
    
    // Resource routes
    Route::resource('playlists', PlaylistController::class);
    Route::resource('favorites', FavoriteController::class);
    Route::post('playlists/{playlist}/tracks', [\App\Http\Controllers\PlaylistTrackController::class, 'store'])
        ->name('playlists.tracks.store');
    Route::delete('playlists/{playlist}/tracks/{track}', [\App\Http\Controllers\PlaylistTrackController::class, 'destroy'])
        ->name('playlists.tracks.destroy');
});
