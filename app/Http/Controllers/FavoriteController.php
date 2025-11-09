<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Requests\UpdateFavoriteRequest;
use App\Models\Favorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        try {
            $userId = session('user_id');
            $favorites = Favorite::byUser($userId)->latest()->paginate(15);

            return view('favorite.index', compact('favorites'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Failed to load favorites.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFavoriteRequest $request): RedirectResponse
    {
        try {
            $userId = session('user_id');
            $validated = $request->validated();

            // Check for duplicates
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View|RedirectResponse
    {
        try {
            $userId = session('user_id');
            $favorite = Favorite::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            return view('favorite.edit', compact('favorite'));
        } catch (\Exception $e) {
            return redirect()->route('favorites.index')
                ->with('error', 'Favorite not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavoriteRequest $request, string $id): RedirectResponse
    {
        try {
            $userId = session('user_id');
            $favorite = Favorite::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $favorite->update([
                'note' => $request->validated()['note'],
            ]);

            return redirect()->route('favorites.index')
                ->with('success', 'Note updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update note. Please try again.')
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
            $favorite = Favorite::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $favorite->delete();

            return redirect()->back()
                ->with('success', 'Song removed from favorites!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to remove favorite.');
        }
    }
}

