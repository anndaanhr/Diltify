@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-4xl font-bold text-white mb-8">Create New Playlist</h1>

    <div class="bg-spotify-gray rounded-lg p-8 border border-spotify-dark">
        <form action="{{ route('playlists.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-white mb-2">Playlist Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-spotify-dark border border-gray-700 rounded-lg text-white placeholder-spotify-text focus:outline-none focus:ring-2 focus:ring-spotify-green focus:border-transparent"
                    placeholder="Enter playlist name">
            </div>

            <div class="flex space-x-4">
                <button type="submit"
                    class="bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-3 rounded-full transition-colors">
                    Create Playlist
                </button>
                <a href="{{ route('playlists.index') }}"
                    class="bg-spotify-dark hover:bg-spotify-gray text-white font-medium px-6 py-3 rounded-full transition-colors border border-gray-700">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

