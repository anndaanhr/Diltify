@php
    $metadata = $metadata ?? [];
    $playlists = $playlists ?? collect();
    $playerMeta = [
        'album' => $metadata['album'] ?? null,
        'genre' => $metadata['genre'] ?? null,
        'duration' => $metadata['duration'] ?? null,
    ];
@endphp
<div class="group bg-spotify-gray/60 hover:bg-spotify-dark p-5 rounded-xl transition-all duration-200 cursor-pointer shadow-lg shadow-black/10 backdrop-blur-sm border border-transparent hover:border-spotify-green/30">
    <div class="relative mb-4">
        <div class="aspect-square bg-spotify-dark rounded-lg overflow-hidden relative">
            @if(isset($artworkUrl) && $artworkUrl)
                <img src="{{ $artworkUrl }}" alt="{{ $trackName }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-spotify-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
            @endif
            <!-- Play Button Overlay -->
            @if(isset($previewUrl) && $previewUrl)
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 flex items-center justify-center transition-all duration-200">
                    <button onclick='playPreview("{{ $previewUrl }}", "{{ addslashes($trackName) }}", "{{ addslashes($artistName) }}", "{{ $artworkUrl ?? '' }}", @json($playerMeta))' class="opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all duration-200 w-12 h-12 bg-spotify-green rounded-full flex items-center justify-center hover:scale-110">
                        <svg class="w-6 h-6 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="min-h-[60px]">
        <h3 class="font-semibold text-white truncate mb-1" title="{{ $trackName }}">{{ $trackName }}</h3>
        <p class="text-sm text-spotify-text truncate" title="{{ $artistName }}">{{ $artistName }}</p>
    </div>
    @if(!empty(array_filter($metadata)))
        <div class="mt-4 space-y-1 text-xs text-spotify-text">
            @if(!empty($metadata['album']))
                <div class="flex items-center space-x-2">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-spotify-dark/80">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                    </span>
                    <span class="truncate">{{ $metadata['album'] }}</span>
                </div>
            @endif
            @if(!empty($metadata['genre']) || !empty($metadata['releaseYear']))
                <div class="flex items-center space-x-2">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-spotify-dark/80">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.657 0 3-.895 3-2s-1.343-2-3-2-3 .895-3 2 1.343 2 3 2zm0 12v.01"></path>
                        </svg>
                    </span>
                    <span class="truncate">
                        @if(!empty($metadata['genre']))
                            {{ $metadata['genre'] }}
                        @endif
                        @if(!empty($metadata['releaseYear']))
                            • {{ $metadata['releaseYear'] }}
                        @endif
                    </span>
                </div>
            @endif
            @if(!empty($metadata['duration']))
                <div class="flex items-center space-x-2">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-spotify-dark/80">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l3 3M5.455 19.545A9 9 0 1119.545 5.455 9 9 0 015.455 19.545z"></path>
                        </svg>
                    </span>
                    <span class="truncate">{{ $metadata['duration'] }}</span>
                </div>
            @endif
            @if(!empty($metadata['trackViewUrl']))
                <a href="{{ $metadata['trackViewUrl'] }}" target="_blank" rel="noopener" class="inline-flex items-center space-x-2 text-spotify-green hover:text-green-400 transition-colors">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-spotify-green/10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656m-2.121-2.12a1.5 1.5 0 000 2.121m4.95-9.192a7 7 0 010 9.9m2.829-12.728a10 10 0 010 14.142M5 8h4M5 12h4m-4 4h4"></path>
                        </svg>
                    </span>
                    <span>Listen on Apple Music</span>
                </a>
            @endif
        </div>
    @endif
    @if(isset($showActions) && $showActions)
        <div class="mt-5 space-y-3">
            <form action="{{ route('music.add-to-favorite') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="track_name" value="{{ $trackName }}">
                <input type="hidden" name="artist_name" value="{{ $artistName }}">
                <input type="hidden" name="preview_url" value="{{ $previewUrl ?? '' }}">
                <input type="hidden" name="artwork_url" value="{{ $artworkUrl ?? '' }}">
                <button type="submit" class="w-full px-4 py-2.5 bg-spotify-green hover:bg-green-500 text-black text-sm font-semibold rounded-full transition-all duration-200 shadow shadow-spotify-green/20">
                    Add to Favorites
                </button>
            </form>

            @if($playlists->count())
                <details class="group playlist-menu relative">
                    <summary class="flex items-center justify-between bg-spotify-dark/80 hover:bg-spotify-dark px-4 py-2.5 rounded-full text-sm text-spotify-text cursor-pointer select-none">
                        <span>Add to Playlist</span>
                        <svg class="w-4 h-4 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <div class="absolute z-20 w-full mt-2 bg-spotify-gray/95 backdrop-blur border border-spotify-dark rounded-xl shadow-xl overflow-hidden">
                        <div class="max-h-48 overflow-y-auto scrollbar-dark py-2">
                            @foreach($playlists as $playlist)
                                <form action="{{ route('playlists.tracks.store', $playlist->id) }}" method="POST" class="block">
                                    @csrf
                                    <input type="hidden" name="playlist_id" value="{{ $playlist->id }}">
                                    <input type="hidden" name="track_id" value="{{ $trackId }}">
                                    <input type="hidden" name="track_name" value="{{ $trackName }}">
                                    <input type="hidden" name="artist_name" value="{{ $artistName }}">
                                    <input type="hidden" name="collection_name" value="{{ $metadata['album'] ?? '' }}">
                                    <input type="hidden" name="collection_artist_name" value="{{ $metadata['albumArtist'] ?? '' }}">
                                    <input type="hidden" name="primary_genre_name" value="{{ $metadata['genre'] ?? '' }}">
                                    <input type="hidden" name="track_time_millis" value="{{ $trackTimeMillis ?? '' }}">
                                    <input type="hidden" name="release_date" value="{{ $releaseDate ?? '' }}">
                                    <input type="hidden" name="preview_url" value="{{ $previewUrl ?? '' }}">
                                    <input type="hidden" name="artwork_url" value="{{ $artworkUrl ?? '' }}">
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-spotify-text hover:text-white hover:bg-spotify-dark/70 transition-colors">
                                        {{ $playlist->name }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </details>
            @else
                <a href="{{ route('playlists.create') }}" class="block w-full px-4 py-2.5 text-center text-sm text-spotify-text hover:text-white bg-spotify-dark/80 hover:bg-spotify-dark rounded-full transition-all duration-200">
                    Create a playlist first
                </a>
            @endif
        </div>
    @endif
</div>

