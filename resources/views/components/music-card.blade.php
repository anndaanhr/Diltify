@php
    $metadata = $metadata ?? [];
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
</div>