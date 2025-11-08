@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-4xl font-bold text-white mb-8">Edit Note</h1>

    <div class="bg-spotify-gray rounded-lg p-8 border border-spotify-dark">
        <!-- Song Info -->
        <div class="flex items-center space-x-4 mb-6 pb-6 border-b border-spotify-dark">
            @if($favorite->artwork_url)
                <img src="{{ $favorite->artwork_url }}" alt="{{ $favorite->track_name }}" class="w-16 h-16 rounded-lg object-cover">
            @else
                <div class="w-16 h-16 bg-spotify-dark rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-spotify-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
            @endif
            <div>
                <h3 class="text-white font-semibold">{{ $favorite->track_name }}</h3>
                <p class="text-spotify-text text-sm">{{ $favorite->artist_name }}</p>
            </div>
        </div>

        <form action="{{ route('favorites.update', $favorite->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="note" class="block text-sm font-medium text-white mb-2">Note</label>
                <textarea id="note" name="note" rows="4"
                    class="w-full px-4 py-3 bg-spotify-dark border border-gray-700 rounded-lg text-white placeholder-spotify-text focus:outline-none focus:ring-2 focus:ring-spotify-green focus:border-transparent resize-none"
                    placeholder="Add a note about this song...">{{ old('note', $favorite->note) }}</textarea>
            </div>

            <div class="flex space-x-4">
                <button type="submit"
                    class="bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-3 rounded-full transition-colors">
                    Update Note
                </button>
                <a href="{{ route('favorites.index') }}"
                    class="bg-spotify-dark hover:bg-spotify-gray text-white font-medium px-6 py-3 rounded-full transition-colors border border-gray-700">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

