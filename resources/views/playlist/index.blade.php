@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-bold text-white">Your Playlists</h1>
        <a href="{{ route('playlists.create') }}" class="bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-3 rounded-full transition-colors">
            Create Playlist
        </a>
    </div>

    @if($playlists->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
            @foreach($playlists as $playlist)
                @include('components.playlist-card', ['playlist' => $playlist])
            @endforeach
        </div>
    @else
        <div class="bg-spotify-gray rounded-lg p-12 text-center border border-spotify-dark">
            <svg class="w-24 h-24 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
            </svg>
            <p class="text-xl text-spotify-text mb-4">You don't have any playlists yet.</p>
            <a href="{{ route('playlists.create') }}" class="inline-block bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-3 rounded-full transition-colors">
                Create Your First Playlist
            </a>
        </div>
    @endif
</div>
@endsection

