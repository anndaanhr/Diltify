<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Favorite;
use App\Services\ItunesService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected ItunesService $itunesService;

    public function __construct(ItunesService $itunesService)
    {
        $this->itunesService = $itunesService;
    }

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

            // Fetch music sections
            $hotToday = $this->itunesService->getHotToday(10);
            $hits = $this->itunesService->getHits(10);
            $recommendations = $this->itunesService->getRecommendations(10);
            $chartLeaderboard = $this->itunesService->getChartLeaderboard(50);
            
            // Genre sections
            $genres = ['Pop', 'Rock', 'Hip-Hop', 'R&B', 'Electronic'];
            $genreSections = [];
            foreach ($genres as $genre) {
                $genreSections[$genre] = $this->itunesService->getGenreSongs($genre, 10);
            }

            // Get user playlists for music-card component
            $userPlaylists = Playlist::byUser($userId)
                ->orderByDesc('created_at')
                ->get();

            return view('dashboard.index', compact(
                'playlists', 
                'favorites', 
                'playlistCount', 
                'favoriteCount',
                'hotToday',
                'hits',
                'recommendations',
                'chartLeaderboard',
                'genreSections',
                'userPlaylists'
            ));
        } catch (\Exception $e) {
            return view('dashboard.index', [
                'playlists' => collect(),
                'favorites' => collect(),
                'playlistCount' => 0,
                'favoriteCount' => 0,
                'hotToday' => [],
                'hits' => [],
                'recommendations' => [],
                'chartLeaderboard' => [],
                'genreSections' => [],
                'userPlaylists' => collect(),
            ]);
        }
    }

    /**
     * Get leaderboard page content via AJAX.
     */
    public function getLeaderboardPage(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $itemsPerPage = 10;
            
            // Fetch all leaderboard data
            $chartLeaderboard = $this->itunesService->getChartLeaderboard(50);
            
            $totalPages = ceil(count($chartLeaderboard) / $itemsPerPage);
            $startIndex = ($page - 1) * $itemsPerPage;
            $paginatedLeaderboard = array_slice($chartLeaderboard, $startIndex, $itemsPerPage);
            
            // Get user playlists for music-card component
            $userId = session('user_id');
            $userPlaylists = Playlist::byUser($userId)
                ->orderByDesc('created_at')
                ->get();
            
            // Render leaderboard content
            $content = view('dashboard._leaderboard-content', [
                'paginatedLeaderboard' => $paginatedLeaderboard,
                'startIndex' => $startIndex,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'userPlaylists' => $userPlaylists,
            ])->render();
            
            return response()->json([
                'success' => true,
                'content' => $content,
                'currentPage' => $page,
                'totalPages' => $totalPages,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load leaderboard page.',
            ], 500);
        }
    }
}

