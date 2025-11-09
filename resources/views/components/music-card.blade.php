@php
    $metadata = $metadata ?? [];
    $playlists = $playlists ?? collect();
    $playerMeta = [
        'album' => $metadata['album'] ?? null,
        'genre' => $metadata['genre'] ?? null,
        'duration' => $metadata['duration'] ?? null,
    ];
    $trackId = $trackId ?? null;
    $hasLink = $trackId !== null;
@endphp
<div class="group {{ $hasLink ? '' : 'cursor-default' }} bg-spotify-gray/60 hover:bg-spotify-dark p-5 rounded-xl transition-all duration-200 shadow-lg shadow-black/10 backdrop-blur-sm border border-transparent hover:border-spotify-green/30">
    @if($hasLink)
        <a href="{{ route('music.track.show', $trackId) }}" class="block">
    @endif
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
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 flex items-center justify-center transition-all duration-200" onclick="event.preventDefault(); event.stopPropagation(); playPreview('{{ $previewUrl }}', '{{ addslashes($trackName) }}', '{{ addslashes($artistName) }}', '{{ $artworkUrl ?? '' }}', @json($playerMeta));">
                    <button class="opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all duration-200 w-12 h-12 bg-spotify-green rounded-full flex items-center justify-center hover:scale-110">
                        <svg class="w-6 h-6 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="min-h-[50px]">
        <h3 class="font-semibold text-white truncate mb-1" title="{{ $trackName }}">{{ $trackName }}</h3>
        <p class="text-sm text-spotify-text truncate" title="{{ $artistName }}">{{ $artistName }}</p>
    </div>
    @if($hasLink)
        </a>
    @endif

    {{-- Favorite / Playlist Actions (show add/remove favorite) --}}
    @php
        $favorite = null;
        $userId = session('user_id');
        if ($userId && !empty($trackName) && !empty($artistName)) {
            $favorite = \App\Models\Favorite::where('user_id', $userId)
                ->where('track_name', $trackName)
                ->where('artist_name', $artistName)
                ->first();
        }
    @endphp

    <div class="mt-4 flex items-center gap-3">
        @if(isset($favorite) && $favorite)
            <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST" onsubmit="return confirm('Remove from favorites?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-full transition-colors">
                    Remove
                </button>
            </form>
        @else
            <form action="{{ route('music.add-to-favorite') }}" method="POST">
                @csrf
                <input type="hidden" name="track_name" value="{{ $trackName }}">
                <input type="hidden" name="artist_name" value="{{ $artistName }}">
                <input type="hidden" name="preview_url" value="{{ $previewUrl ?? '' }}">
                <input type="hidden" name="artwork_url" value="{{ $artworkUrl ?? '' }}">
                <button type="submit" class="px-3 py-1 bg-spotify-green hover:bg-green-500 text-black text-sm font-medium rounded-full transition-colors">
                    Add to Favorite
                </button>
            </form>
        @endif

        @if($playlists && $playlists->count())
            <details class="group playlist-menu relative inline-block">
                <summary class="flex items-center justify-between bg-spotify-dark/80 hover:bg-spotify-dark px-3 py-1 rounded-full text-sm text-spotify-text cursor-pointer select-none">
                    <span class="text-xs">Add to Playlist</span>
                    <svg class="w-3 h-3 ml-2 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <div class="absolute z-20 mt-2 bg-spotify-gray/95 backdrop-blur border border-spotify-dark rounded-xl shadow-xl overflow-hidden min-w-[180px]">
                    <div class="max-h-48 overflow-y-auto scrollbar-dark py-2">
                        @foreach($playlists as $playlist)
                            <form action="{{ route('playlists.tracks.store', $playlist->id) }}" method="POST" class="block">
                                @csrf
                                <input type="hidden" name="playlist_id" value="{{ $playlist->id }}">
                                <input type="hidden" name="track_id" value="{{ $trackId ?? '' }}">
                                <input type="hidden" name="track_name" value="{{ $trackName }}">
                                <input type="hidden" name="artist_name" value="{{ $artistName }}">
                                <input type="hidden" name="collection_name" value="{{ $metadata['album'] ?? '' }}">
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
        @endif
    </div>
</div>
