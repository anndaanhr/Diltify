@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">{{ $playlist->name }}</h1>
            <p class="text-spotify-text">Playlist</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('playlists.edit', $playlist->id) }}"
               class="bg-spotify-gray hover:bg-spotify-dark text-white font-medium px-6 py-3 rounded-full transition-colors border border-gray-700">
                Edit
            </a>
        </div>
    </div>

    <form action="{{ route('music.search') }}" method="POST" class="mb-8">
        @csrf
        <div class="relative">
            <input type="text" name="query" value="{{ $query ?? '' }}" required
                   class="w-full px-6 py-4 bg-spotify-gray border border-gray-700 rounded-full text-white placeholder-spotify-text focus:outline-none focus:ring-2 focus:ring-spotify-green focus:border-transparent text-lg"
                   placeholder="Search to add more songs...">
            <button type="submit" class="absolute right-2 top-2 bg-spotify-green hover:bg-green-600 text-black p-3 rounded-full transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>
    </form>
    @if($tracks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($tracks as $track)
                @php
                    $playerMeta = [
                        'album' => $track->collection_name,
                        'genre' => $track->primary_genre_name,
                        'duration' => $track->track_time_millis ? gmdate('i:s', (int) ($track->track_time_millis / 1000)) : null,
                    ];
                @endphp
                <div class="bg-spotify-gray/70 border border-transparent hover:border-spotify-green/30 rounded-xl p-5 shadow-lg shadow-black/10 transition-all duration-200 backdrop-blur-sm group">
                    <div class="flex items-start space-x-4">
                        <div class="relative w-20 h-20 rounded-lg overflow-hidden bg-spotify-dark">
                            @if($track->artwork_url)
                                <img src="{{ $track->artwork_url }}" alt="{{ $track->track_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-spotify-text">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    </svg>
                                </div>
                            @endif
                            @if($track->preview_url)
                                <button onclick='playPreview("{{ $track->preview_url }}", "{{ addslashes($track->track_name) }}", "{{ addslashes($track->artist_name) }}", "{{ $track->artwork_url ?? '' }}", @json($playerMeta))' class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/50 transition-colors duration-200">
                                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 w-12 h-12 bg-spotify-green rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between space-x-2">
                                <div>
                                    <h3 class="text-white font-semibold truncate" title="{{ $track->track_name }}">{{ $track->track_name }}</h3>
                                    <p class="text-sm text-spotify-text truncate" title="{{ $track->artist_name }}">{{ $track->artist_name }}</p>
                                </div>
                                <form action="{{ route('playlists.tracks.destroy', [$playlist->id, $track->id]) }}" method="POST" onsubmit="return confirm('Remove this song from the playlist?')" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-spotify-text hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-spotify-text">
                                @if($track->collection_name)
                                    <div class="flex items-center space-x-1.5 truncate">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                        <span class="truncate">{{ $track->collection_name }}</span>
                                    </div>
                                @endif
                                @if($track->primary_genre_name)
                                    <div class="flex items-center space-x-1.5 truncate">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.657 0 3-.895 3-2s-1.343-2-3-2-3 .895-3 2 1.343 2 3 2zm0 12v.01"></path>
                                        </svg>
                                        <span class="truncate">{{ $track->primary_genre_name }}</span>
                                    </div>
                                @endif
                                @if($track->track_time_millis)
                                    <div class="flex items-center space-x-1.5 truncate">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l3 3M5.455 19.545A9 9 0 1119.545 5.455 9 9 0 015.455 19.545z"></path>
                                        </svg>
                                        <span class="truncate">{{ gmdate('i:s', (int) ($track->track_time_millis / 1000)) }}</span>
                                    </div>
                                @endif
                                @if($track->release_date)
                                    <div class="flex items-center space-x-1.5 truncate">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a1 1 0 00-1-1h-2a1 1 0 00-1 1v2H9V3a1 1 0 00-1-1H6a1 1 0 00-1 1v2H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="truncate">{{ optional($track->release_date)->format('d M Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-spotify-gray rounded-lg p-8 border border-spotify-dark text-center">
            <svg class="w-24 h-24 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
            </svg>
            <p class="text-spotify-text text-lg">This playlist is empty.</p>
            <p class="text-spotify-text">Add songs to your playlist from the search page.</p>
        </div>
    @endif
</div>
@endsection