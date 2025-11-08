<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchMusicRequest;
use App\Http\Requests\StoreFavoriteRequest;
use App\Models\Favorite;
use App\Models\Playlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class MusicController extends Controller
{

    
    public function index()
    {
        return view('music.search', [
            'results' => [],
            'query' => null,
            'userPlaylists' => $this->getUserPlaylists(),
        ]);
    }

    /**
     * Search for music using iTunes API.
     */
    public function search(SearchMusicRequest $request)
    {
        try {
            $query = $request->input('query');
            $results = [];

            try {
                $response = Http::get('https://itunes.apple.com/search', [
                    'term' => $query,
                    'media' => 'music',
                    'limit' => 24,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['results']) && is_array($data['results'])) {
                        $results = array_map(function ($item) {
                            $trackTimeMillis = $item['trackTimeMillis'] ?? null;
                            $releaseDate = $item['releaseDate'] ?? null;

                            return [
                                'trackId' => $item['trackId'] ?? null,
                                'trackName' => $item['trackName'] ?? 'Unknown',
                                'artistName' => $item['artistName'] ?? 'Unknown',
                                'previewUrl' => $item['previewUrl'] ?? null,
                                'artworkUrl' => $item['artworkUrl100'] ?? $item['artworkUrl60'] ?? null,
                                'collectionName' => $item['collectionName'] ?? null,
                                'collectionArtistName' => $item['collectionArtistName'] ?? $item['artistName'] ?? null,
                                'primaryGenreName' => $item['primaryGenreName'] ?? null,
                                'trackTimeMillis' => $trackTimeMillis,
                                'durationLabel' => $trackTimeMillis ? gmdate('i:s', (int) ($trackTimeMillis / 1000)) : null,
                                'releaseDate' => $releaseDate,
                                'releaseYear' => $releaseDate ? date('Y', strtotime($releaseDate)) : null,
                                'country' => $item['country'] ?? null,
                                'currency' => $item['currency'] ?? null,
                                'trackViewUrl' => $item['trackViewUrl'] ?? null,
                                'collectionViewUrl' => $item['collectionViewUrl'] ?? null,
                            ];
                        }, $data['results']);
                    }
                }
            } catch (\Exception $e) {
                // API error, results will be empty
            }

            return view('music.search', [
                'results' => $results,
                'query' => $query,
                'userPlaylists' => $this->getUserPlaylists(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Search failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Add a song to favorites.
     */
    public function addToFavorite(StoreFavoriteRequest $request): RedirectResponse
    {
        try {
            $userId = session('user_id');
            $validated = $request->validated();

            $existing = Favorite::where('user_id', $userId)
                ->where('track_name', $validated['track_name'])
                ->where('artist_name', $validated['artist_name'])
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'This song is already in your favorites.');
            }

            Favorite::create([
                'user_id' => $userId,
                'track_name' => $validated['track_name'],
                'artist_name' => $validated['artist_name'],
                'preview_url' => $validated['preview_url'] ?? null,
                'artwork_url' => $validated['artwork_url'] ?? null,
            ]);

            return redirect()->back()
                ->with('success', 'Song added to favorites!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add to favorites. Please try again.');
        }
    }

    /**
     * Get the authenticated user's playlists.
     */
    protected function getUserPlaylists(): Collection
    {
        $userId = session('user_id');

        if (!$userId) {
            return collect();
        }

        return Playlist::byUser($userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function ajaxSearch(Request $request)
    {
        
        $request->validate([
            'query' => 'required|string|min:2',
            'playlist_id' => 'required|exists:playlists,id',
        ]);

        $query = $request->input('query');
        $playlist = Playlist::findOrFail($request->input('playlist_id'));

        $apiResponse = $this->callApi($query);
        $results = $apiResponse['results'];

        $html = '';
        if (count($results) > 0) {
            foreach ($results as $result) {
                $html .= view('music._search_result_item', [
                    'result' => $result,
                    'playlist' => $playlist
                ])->render();
            }
        } else {
            $html = '<p class="text-spotify-text text-center py-4">No results found for "' . e($query) . '".</p>';
        }

        return response($html);
    }
}

