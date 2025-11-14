@extends('layouts.app')

@php
    $userPlaylists = $userPlaylists ?? collect();
    $playerMeta = [
        'album' => $track['collectionName'] ?? null,
        'genre' => $track['primaryGenreName'] ?? null,
        'duration' => $track['durationLabel'] ?? null,
    ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-spotify-text hover:text-white transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>
    </div>

    <!-- Track Header -->
    <div class="flex flex-col md:flex-row gap-8 mb-8">
        <!-- Artwork -->
        <div class="flex-shrink-0">
            <div class="w-full md:w-80 aspect-square bg-spotify-dark rounded-xl overflow-hidden shadow-2xl">
                @if($track['artworkUrl'])
                    <img src="{{ $track['artworkUrl'] }}" alt="{{ $track['trackName'] }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-32 h-32 text-spotify-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Track Info -->
        <div class="flex-1 flex flex-col justify-end">
            <div class="mb-4">
                <p class="text-sm text-spotify-text mb-2">Song</p>
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">{{ $track['trackName'] }}</h1>
                <div class="flex items-center gap-4 mb-6">
                    @if($track['artistName'])
                        <a href="{{ $track['artistViewUrl'] ?? '#' }}" target="_blank" class="text-xl text-spotify-text hover:text-white transition-colors">
                            {{ $track['artistName'] }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Play Button -->
            @if($track['previewUrl'])
                <div class="flex items-center gap-4 mb-6">
                    <button onclick='playPreview("{{ $track['previewUrl'] }}", "{{ addslashes($track['trackName']) }}", "{{ addslashes($track['artistName']) }}", "{{ $track['artworkUrl'] ?? '' }}", @json($playerMeta))' class="w-16 h-16 bg-spotify-green hover:bg-green-500 rounded-full flex items-center justify-center transition-all duration-200 shadow-lg shadow-spotify-green/30 hover:scale-110">
                        <svg class="w-8 h-8 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </button>
                    <span class="text-spotify-text text-sm">Preview</span>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-wrap gap-3">
                @if(isset($favorite) && $favorite)
                    <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full transition-all duration-200 shadow shadow-black/20">
                            Remove from Favorites
                        </button>
                    </form>
                @else
                    <form action="{{ route('music.add-to-favorite') }}" method="POST">
                        @csrf
                        <input type="hidden" name="track_name" value="{{ $track['trackName'] }}">
                        <input type="hidden" name="artist_name" value="{{ $track['artistName'] }}">
                        <input type="hidden" name="preview_url" value="{{ $track['previewUrl'] ?? '' }}">
                        <input type="hidden" name="artwork_url" value="{{ $track['artworkUrl'] ?? '' }}">
                        <button type="submit" class="px-6 py-3 bg-spotify-green hover:bg-green-500 text-black font-semibold rounded-full transition-all duration-200 shadow shadow-spotify-green/20">
                            Add to Favorites
                        </button>
                    </form>
                @endif

                @if($userPlaylists->count())
                    <details class="group playlist-menu relative inline-block">
                        <summary class="flex items-center justify-between bg-spotify-dark/80 hover:bg-spotify-dark px-6 py-3 rounded-full text-sm text-spotify-text cursor-pointer select-none">
                            <span>Add to Playlist</span>
                            <svg class="w-4 h-4 ml-2 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="absolute z-20 mt-2 bg-spotify-gray/95 backdrop-blur border border-spotify-dark rounded-xl shadow-xl overflow-hidden min-w-[200px]">
                            <div class="max-h-48 overflow-y-auto scrollbar-dark py-2">
                                @foreach($userPlaylists as $playlist)
                                    <form action="{{ route('playlists.tracks.store', $playlist->id) }}" method="POST" class="block">
                                        @csrf
                                        <input type="hidden" name="playlist_id" value="{{ $playlist->id }}">
                                        <input type="hidden" name="track_id" value="{{ $track['trackId'] }}">
                                        <input type="hidden" name="track_name" value="{{ $track['trackName'] }}">
                                        <input type="hidden" name="artist_name" value="{{ $track['artistName'] }}">
                                        <input type="hidden" name="collection_name" value="{{ $track['collectionName'] ?? '' }}">
                                        <input type="hidden" name="collection_artist_name" value="{{ $track['collectionArtistName'] ?? '' }}">
                                        <input type="hidden" name="primary_genre_name" value="{{ $track['primaryGenreName'] ?? '' }}">
                                        <input type="hidden" name="track_time_millis" value="{{ $track['trackTimeMillis'] ?? '' }}">
                                        <input type="hidden" name="release_date" value="{{ $track['releaseDate'] ?? '' }}">
                                        <input type="hidden" name="preview_url" value="{{ $track['previewUrl'] ?? '' }}">
                                        <input type="hidden" name="artwork_url" value="{{ $track['artworkUrl'] ?? '' }}">
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-spotify-text hover:text-white hover:bg-spotify-dark/70 transition-colors">
                                            {{ $playlist->name }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    </details>
                @endif
            </div>
        </div>
    </div>

    <!-- Track Details -->
    <div class="bg-spotify-gray/60 rounded-xl p-8 border border-spotify-dark">
        <h2 class="text-2xl font-bold text-white mb-6">Track Details</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Album -->
            @if($track['collectionName'])
                <div>
                    <p class="text-spotify-text text-sm mb-1">Album</p>
                    <p class="text-white font-medium">
                        @if($track['collectionViewUrl'])
                            <a href="{{ $track['collectionViewUrl'] }}" target="_blank" class="hover:text-spotify-green transition-colors">
                                {{ $track['collectionName'] }}
                            </a>
                        @else
                            {{ $track['collectionName'] }}
                        @endif
                    </p>
                </div>
            @endif

            <!-- Genre -->
            @if($track['primaryGenreName'])
                <div>
                    <p class="text-spotify-text text-sm mb-1">Genre</p>
                    <p class="text-white font-medium">{{ $track['primaryGenreName'] }}</p>
                </div>
            @endif

            <!-- Duration -->
            @if($track['durationLabel'])
                <div>
                    <p class="text-spotify-text text-sm mb-1">Duration</p>
                    <p class="text-white font-medium">{{ $track['durationLabel'] }}</p>
                </div>
            @endif

            <!-- Release Date -->
            @if($track['releaseDate'])
                <div>
                    <p class="text-spotify-text text-sm mb-1">Release Date</p>
                    <p class="text-white font-medium">{{ date('F d, Y', strtotime($track['releaseDate'])) }}</p>
                </div>
            @endif

            <!-- Track Number -->
            @if($track['trackNumber'] && $track['trackCount'])
                <div>
                    <p class="text-spotify-text text-sm mb-1">Track Number</p>
                    <p class="text-white font-medium">{{ $track['trackNumber'] }} of {{ $track['trackCount'] }}</p>
                </div>
            @endif

            <!-- Disc Number -->
            @if($track['discNumber'] && $track['discCount'])
                <div>
                    <p class="text-spotify-text text-sm mb-1">Disc Number</p>
                    <p class="text-white font-medium">{{ $track['discNumber'] }} of {{ $track['discCount'] }}</p>
                </div>
            @endif

            <!-- Price -->
            @if($track['trackPrice'])
                <div>
                    <p class="text-spotify-text text-sm mb-1">Price</p>
                    <p class="text-white font-medium">
                        {{ $track['currency'] ?? 'USD' }} {{ number_format($track['trackPrice'], 2) }}
                    </p>
                </div>
            @endif

            <!-- Country -->
            @if($track['country'])
                <div>
                    <p class="text-spotify-text text-sm mb-1">Country</p>
                    <p class="text-white font-medium">{{ $track['country'] }}</p>
                </div>
            @endif
        </div>

        <!-- External Links -->
        @if($track['trackViewUrl'] || $track['collectionViewUrl'] || $track['artistViewUrl'])
            <div class="mt-8 pt-8 border-t border-spotify-dark">
                <h3 class="text-xl font-bold text-white mb-4">Listen on</h3>
                <div class="flex flex-wrap gap-4">
                    @if($track['trackViewUrl'])
                        <a href="{{ $track['trackViewUrl'] }}" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 bg-spotify-dark hover:bg-spotify-gray rounded-full text-white transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                            Apple Music
                        </a>
                    @endif
                    @if($track['collectionViewUrl'])
                        <a href="{{ $track['collectionViewUrl'] }}" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 bg-spotify-dark hover:bg-spotify-gray rounded-full text-white transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                            View Album
                        </a>
                    @endif
                    @if($track['artistViewUrl'])
                        <a href="{{ $track['artistViewUrl'] }}" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 bg-spotify-dark hover:bg-spotify-gray rounded-full text-white transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                            View Artist
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

