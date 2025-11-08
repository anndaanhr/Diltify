<a href="{{ route('playlists.show', $playlist->id) }}" class="block bg-spotify-gray/70 hover:bg-spotify-dark p-5 rounded-xl transition-all duration-200 group border border-transparent hover:border-spotify-green/30 shadow-lg shadow-black/10">
    <div class="aspect-square bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg mb-4 flex items-center justify-center">
        <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
        </svg>
    </div>
    <h3 class="font-semibold text-white truncate mb-1">{{ $playlist->name }}</h3>
    <p class="text-xs text-spotify-text uppercase tracking-wide">{{ $playlist->tracks_count ?? $playlist->tracks->count() ?? 0 }} songs</p>
</a>

