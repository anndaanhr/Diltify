<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Favorite;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        try {
            $userId = session('user_id');
            
            $playlists = Playlist::byUser($userId)
                ->withCount('tracks')
                ->latest()
                ->take(6)
                ->get();
            $favorites = Favorite::byUser($userId)->latest()->take(6)->get();
            $playlistCount = Playlist::byUser($userId)->count();
            $favoriteCount = Favorite::byUser($userId)->count();

            return view('dashboard.index', compact('playlists', 'favorites', 'playlistCount', 'favoriteCount'));
        } catch (\Exception $e) {
            return view('dashboard.index', [
                'playlists' => collect(),
                'favorites' => collect(),
                'playlistCount' => 0,
                'favoriteCount' => 0,
            ]);
        }
    }
}

