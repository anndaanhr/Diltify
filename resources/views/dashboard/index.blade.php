@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Welcome back, {{ session('user_name', 'User') }}!</h1>
        <p class="text-spotify-text">Here's what's happening with your music today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-spotify-gray rounded-lg p-6 border border-spotify-dark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-spotify-text text-sm mb-1">Total Playlists</p>
                    <p class="text-3xl font-bold text-white">{{ $playlistCount }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-spotify-gray rounded-lg p-6 border border-spotify-dark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-spotify-text text-sm mb-1">Favorite Songs</p>
                    <p class="text-3xl font-bold text-white">{{ $favoriteCount }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Playlists -->
    @if($playlists->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-white">Your Playlists</h2>
                <a href="{{ route('playlists.index') }}" class="text-spotify-text hover:text-white text-sm">
                    See all
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($playlists as $playlist)
                    @include('components.playlist-card', ['playlist' => $playlist])
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-8 bg-spotify-gray rounded-lg p-8 text-center border border-spotify-dark">
            <svg class="w-16 h-16 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
            </svg>
            <p class="text-spotify-text mb-4">You don't have any playlists yet.</p>
            <a href="{{ route('playlists.create') }}" class="inline-block bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-2 rounded-full transition-colors">
                Create Playlist
            </a>
        </div>
    @endif

    <!-- Recent Favorites -->
    @if($favorites->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-white">Your Favorite Songs</h2>
                <a href="{{ route('favorites.index') }}" class="text-spotify-text hover:text-white text-sm">
                    See all
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($favorites as $favorite)
                    @include('components.music-card', [
                        'trackName' => $favorite->track_name,
                        'artistName' => $favorite->artist_name,
                        'previewUrl' => $favorite->preview_url,
                        'artworkUrl' => $favorite->artwork_url,
                        'showActions' => false
                    ])
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-8 bg-spotify-gray rounded-lg p-8 text-center border border-spotify-dark">
            <svg class="w-16 h-16 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <p class="text-spotify-text mb-4">You don't have any favorite songs yet.</p>
            <a href="{{ route('music.search') }}" class="inline-block bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-2 rounded-full transition-colors">
                Search Music
            </a>
        </div>
    @endif
</div>
@endsection

