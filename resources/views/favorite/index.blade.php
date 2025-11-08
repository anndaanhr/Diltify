@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-4xl font-bold text-white mb-8">Your Favorite Songs</h1>

    @if($favorites->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-8">
            @foreach($favorites as $favorite)
                <div class="group bg-spotify-gray hover:bg-spotify-dark p-4 rounded-lg transition-all duration-200">
                    <div class="relative mb-4">
                        <div class="aspect-square bg-spotify-dark rounded-lg overflow-hidden relative">
                            @if($favorite->artwork_url)
                                <img src="{{ $favorite->artwork_url }}" alt="{{ $favorite->track_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-spotify-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    </svg>
                                </div>
                            @endif
                            @if($favorite->preview_url)
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 flex items-center justify-center transition-all duration-200">
                                    <button onclick="playPreview('{{ $favorite->preview_url }}', '{{ addslashes($favorite->track_name) }}', '{{ addslashes($favorite->artist_name) }}', '{{ $favorite->artwork_url ?? '' }}')" class="opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all duration-200 w-12 h-12 bg-spotify-green rounded-full flex items-center justify-center hover:scale-110">
                                        <svg class="w-6 h-6 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="min-h-[60px] mb-3">
                        <h3 class="font-semibold text-white truncate mb-1" title="{{ $favorite->track_name }}">{{ $favorite->track_name }}</h3>
                        <p class="text-sm text-spotify-text truncate" title="{{ $favorite->artist_name }}">{{ $favorite->artist_name }}</p>
                        @if($favorite->note)
                            <p class="text-xs text-spotify-text mt-2 line-clamp-2" title="{{ $favorite->note }}">{{ $favorite->note }}</p>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('favorites.edit', $favorite->id) }}" class="flex-1 px-3 py-2 bg-spotify-dark hover:bg-spotify-gray text-white text-sm font-medium rounded-full transition-colors text-center">
                            Edit Note
                        </a>
                        <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Remove from favorites?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-full transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            <div class="pagination">
                {{ $favorites->links() }}
            </div>
        </div>
    @else
        <div class="bg-spotify-gray rounded-lg p-12 text-center border border-spotify-dark">
            <svg class="w-24 h-24 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <p class="text-xl text-spotify-text mb-4">You don't have any favorite songs yet.</p>
            <a href="{{ route('music.search') }}" class="inline-block bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-3 rounded-full transition-colors">
                Search Music
            </a>
        </div>
    @endif
</div>
@endsection

