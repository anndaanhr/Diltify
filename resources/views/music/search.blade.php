@extends('layouts.app')

@section('content')
@php
    $userPlaylists = $userPlaylists ?? collect();
@endphp
<div class="max-w-7xl mx-auto">
    <!-- Search Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-6">Search Music</h1>
        
        <form action="{{ route('music.search') }}" method="POST" class="mb-8">
            @csrf
            <div class="relative">
                <input type="text" name="query" value="{{ $query ?? '' }}" required
                    class="w-full px-6 py-4 bg-spotify-gray border border-gray-700 rounded-full text-white placeholder-spotify-text focus:outline-none focus:ring-2 focus:ring-spotify-green focus:border-transparent text-lg"
                    placeholder="Search for songs, artists, albums...">
                <button type="submit" class="absolute right-2 top-2 bg-spotify-green hover:bg-green-600 text-black p-3 rounded-full transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Results Section -->
    @if(isset($results) && count($results) > 0)
        <div>
            <h2 class="text-2xl font-bold text-white mb-6">
                Search Results{{ $query ? ' for "' . $query . '"' : '' }}
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($results as $result)
                    @include('components.music-card', [
                        'trackId' => $result['trackId'],
                        'trackName' => $result['trackName'],
                        'artistName' => $result['artistName'],
                        'previewUrl' => $result['previewUrl'],
                        'artworkUrl' => $result['artworkUrl'],
                        'showActions' => true,
                        'playlists' => $userPlaylists,
                        'metadata' => [
                            'album' => $result['collectionName'],
                            'albumArtist' => $result['collectionArtistName'],
                            'genre' => $result['primaryGenreName'],
                            'duration' => $result['durationLabel'],
                            'releaseYear' => $result['releaseYear'],
                            'country' => $result['country'],
                            'trackViewUrl' => $result['trackViewUrl'],
                            'collectionViewUrl' => $result['collectionViewUrl'],
                        ],
                        'trackTimeMillis' => $result['trackTimeMillis'],
                        'releaseDate' => $result['releaseDate'],
                    ])
                @endforeach
            </div>
        </div>
    @elseif(isset($query) && $query)
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-xl text-spotify-text mb-2">No results found</p>
            <p class="text-spotify-text">Try searching for a different song or artist.</p>
        </div>
    @else
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <p class="text-xl text-spotify-text mb-2">Start searching for music</p>
            <p class="text-spotify-text">Enter a song name, artist, or album in the search box above.</p>
        </div>
    @endif
</div>
@endsection

