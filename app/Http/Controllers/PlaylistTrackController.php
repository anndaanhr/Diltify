<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTrackToPlaylistRequest;
use App\Models\Playlist;
use App\Models\PlaylistTrack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PlaylistTrackController extends Controller
{
    /**
     * Store a newly created track in the playlist.
     */
    public function store(AddTrackToPlaylistRequest $request, Playlist $playlist): RedirectResponse
    {
        try {
            $userId = session('user_id');

            if (!$userId || $playlist->user_id !== $userId) {
                return redirect()->back()->with('error', 'You are not authorized to modify this playlist.');
            }

            $validated = $request->validated();

            if ($playlist->id !== $validated['playlist_id']) {
                return redirect()->back()->with('error', 'Playlist mismatch. Please try again.');
            }

            $trackId = $validated['track_id'];

            $duplicateQuery = $playlist->tracks();

            if ($trackId) {
                $duplicateQuery->where('track_id', $trackId);
            } else {
                $duplicateQuery->where('track_name', $validated['track_name'])
                    ->where('artist_name', $validated['artist_name']);
            }

            if ($duplicateQuery->exists()) {
                return redirect()->back()->with('error', 'This song is already in the playlist.');
            }

            DB::transaction(function () use ($validated, $playlist) {
                PlaylistTrack::create([
                    'playlist_id' => $playlist->id,
                    'track_id' => $validated['track_id'],
                    'track_name' => $validated['track_name'],
                    'artist_name' => $validated['artist_name'],
                    'collection_name' => $validated['collection_name'],
                    'collection_artist_name' => $validated['collection_artist_name'],
                    'primary_genre_name' => $validated['primary_genre_name'],
                    'track_time_millis' => $validated['track_time_millis'],
                    'release_date' => $validated['release_date'],
                    'preview_url' => $validated['preview_url'],
                    'artwork_url' => $validated['artwork_url'],
                ]);
            });

            return redirect()->back()->with('success', 'Song added to playlist!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add song to playlist. Please try again.');
        }
    }

    /**
     * Remove the specified track from the playlist.
     */
    public function destroy(Playlist $playlist, PlaylistTrack $track): RedirectResponse
    {
        try {
            $userId = session('user_id');

            if (!$userId || $playlist->user_id !== $userId) {
                return redirect()->back()->with('error', 'You are not authorized to modify this playlist.');
            }

            if ($track->playlist_id !== $playlist->id) {
                return redirect()->back()->with('error', 'The selected song does not belong to this playlist.');
            }

            $track->delete();

            return redirect()->back()->with('success', 'Song removed from playlist.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove song from playlist.');
        }
    }
}
