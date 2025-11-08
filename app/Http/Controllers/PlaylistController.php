<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlaylistRequest;
use App\Http\Requests\UpdatePlaylistRequest;
use App\Models\Playlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        try {
            $userId = session('user_id');
            $playlists = Playlist::byUser($userId)
                ->withCount('tracks')
                ->latest()
                ->get();

            return view('playlist.index', compact('playlists'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Failed to load playlists.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('playlist.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlaylistRequest $request): RedirectResponse
    {
        try {
            Playlist::create([
                'user_id' => session('user_id'),
                'name' => $request->validated()['name'],
            ]);

            return redirect()->route('playlists.index')
                ->with('success', 'Playlist created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create playlist. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View|RedirectResponse
    {
        try {
            $userId = session('user_id');
            $playlist = Playlist::where('id', $id)
                ->where('user_id', $userId)
                ->with(['tracks' => function ($query) {
                    $query->orderByDesc('created_at');
                }])
                ->firstOrFail();

            return view('playlist.show', [
                'playlist' => $playlist,
                'tracks' => $playlist->tracks,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('playlists.index')
                ->with('error', 'Playlist not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View|RedirectResponse
    {
        try {
            $userId = session('user_id');
            $playlist = Playlist::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            return view('playlist.edit', compact('playlist'));
        } catch (\Exception $e) {
            return redirect()->route('playlists.index')
                ->with('error', 'Playlist not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlaylistRequest $request, string $id): RedirectResponse
    {
        try {
            $userId = session('user_id');
            $playlist = Playlist::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $playlist->update([
                'name' => $request->validated()['name'],
            ]);

            return redirect()->route('playlists.index')
                ->with('success', 'Playlist updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update playlist. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $userId = session('user_id');
            $playlist = Playlist::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $playlist->delete();

            return redirect()->route('playlists.index')
                ->with('success', 'Playlist deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('playlists.index')
                ->with('error', 'Failed to delete playlist.');
        }
    }
}

