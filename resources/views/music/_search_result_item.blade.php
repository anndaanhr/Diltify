

<div class="flex items-center justify-between p-3 bg-spotify-dark rounded-lg">
    <div class="flex items-center space-x-3 min-w-0">
        <img src="{{ $result['artworkUrl'] ?? asset('images/default-album.png') }}" alt="Album Art" class="w-12 h-12 rounded">
        <div class="min-w-0">
            <p class="text-white font-medium truncate">{{ $result['trackName'] }}</p>
            <p class="text-spotify-text text-sm truncate">{{ $result['artistName'] }}</p>
        </div>
    </div>
    

    <form action="{{ route('playlists.tracks.store', $playlist->id) }}" method="POST" class="add-track-form">
        @csrf
        <input type="hidden" name="playlist_id" value="{{ $playlist->id }}">
        <input type="hidden" name="track_id" value="{{ $result['trackId'] }}">
        <input type="hidden" name="track_name" value="{{ $result['trackName'] }}">
        <input type="hidden" name="artist_name" value="{{ $result['artistName'] }}">
        <input type="hidden" name="preview_url" value="{{ $result['previewUrl'] }}">
        <input type="hidden" name="artwork_url" value="{{ $result['artworkUrl'] }}">
        <input type="hidden" name="collection_name" value="{{ $result['collectionName'] }}">
        <input type="hidden" name="collection_artist_name" value="{{ $result['collectionArtistName'] ?? '' }}">
        <input type="hidden" name="primary_genre_name" value="{{ $result['primaryGenreName'] }}">
        <input type="hidden" name="track_time_millis" value="{{ $result['trackTimeMillis'] }}">
        <input type="hidden" name="release_date" value="{{ $result['releaseDate'] }}">
        
        <button type="submit" class="bg-spotify-green text-black text-sm font-bold py-2 px-4 rounded-full hover:bg-green-600 transition-colors">
            Add
        </button>
    </form>
</div>