@extends('layouts.app')

@php
    $userPlaylists = $userPlaylists ?? collect();
@endphp

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Welcome back, {{ session('user_name', 'User') }}!</h1>
        <p class="text-spotify-text">Here's what's happening with your music today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <div class="bg-spotify-gray rounded-lg p-6 border border-spotify-dark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-spotify-text text-sm mb-1">Total Playlists</p>
                    <p class="text-3xl font-bold text-white">{{ $playlistCount }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-spotify-gray rounded-lg p-6 border border-spotify-dark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-spotify-text text-sm mb-1">Favorite Songs</p>
                    <p class="text-3xl font-bold text-white">{{ $favoriteCount }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Hot Today Section -->
    @if(!empty($hotToday) && count($hotToday) > 0)
        <div class="mb-12">
            @include('components.section-header', ['title' => '🔥 Hot Today'])
            <div class="music-section-scroll">
                <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide">
                    @foreach($hotToday as $result)
                        <div class="flex-shrink-0 w-[180px]">
                            @include('components.music-card', [
                                'trackId' => $result['trackId'],
                                'trackName' => $result['trackName'],
                                'artistName' => $result['artistName'],
                                'previewUrl' => $result['previewUrl'],
                                'artworkUrl' => $result['artworkUrl'],
                                'metadata' => [
                                    'album' => $result['collectionName'],
                                    'albumArtist' => $result['collectionArtistName'],
                                    'genre' => $result['primaryGenreName'],
                                    'duration' => $result['durationLabel'],
                                    'releaseYear' => $result['releaseYear'],
                                    'trackViewUrl' => $result['trackViewUrl'],
                                ],
                                'trackTimeMillis' => $result['trackTimeMillis'],
                                'releaseDate' => $result['releaseDate'],
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Hits Section -->
    @if(!empty($hits) && count($hits) > 0)
        <div class="mb-12">
            @include('components.section-header', ['title' => '🎵 Hits'])
            <div class="music-section-scroll">
                <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide">
                    @foreach($hits as $result)
                        <div class="flex-shrink-0 w-[180px]">
                            @include('components.music-card', [
                                'trackId' => $result['trackId'],
                                'trackName' => $result['trackName'],
                                'artistName' => $result['artistName'],
                                'previewUrl' => $result['previewUrl'],
                                'artworkUrl' => $result['artworkUrl'],
                                'metadata' => [
                                    'album' => $result['collectionName'],
                                    'albumArtist' => $result['collectionArtistName'],
                                    'genre' => $result['primaryGenreName'],
                                    'duration' => $result['durationLabel'],
                                    'releaseYear' => $result['releaseYear'],
                                    'trackViewUrl' => $result['trackViewUrl'],
                                ],
                                'trackTimeMillis' => $result['trackTimeMillis'],
                                'releaseDate' => $result['releaseDate'],
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Recommendations Section -->
    @if(!empty($recommendations) && count($recommendations) > 0)
        <div class="mb-12">
            @include('components.section-header', ['title' => '✨ Rekomendasi'])
            <div class="music-section-scroll">
                <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide">
                    @foreach($recommendations as $result)
                        <div class="flex-shrink-0 w-[180px]">
                            @include('components.music-card', [
                                'trackId' => $result['trackId'],
                                'trackName' => $result['trackName'],
                                'artistName' => $result['artistName'],
                                'previewUrl' => $result['previewUrl'],
                                'artworkUrl' => $result['artworkUrl'],
                                'metadata' => [
                                    'album' => $result['collectionName'],
                                    'albumArtist' => $result['collectionArtistName'],
                                    'genre' => $result['primaryGenreName'],
                                    'duration' => $result['durationLabel'],
                                    'releaseYear' => $result['releaseYear'],
                                    'trackViewUrl' => $result['trackViewUrl'],
                                ],
                                'trackTimeMillis' => $result['trackTimeMillis'],
                                'releaseDate' => $result['releaseDate'],
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Chart Leaderboard Section -->
    @if(!empty($chartLeaderboard) && count($chartLeaderboard) > 0)
        @php
            $currentPage = request()->get('leaderboard_page', 1);
            $itemsPerPage = 10; // 10 items per page (3-4 rows x 3 columns)
            $totalPages = ceil(count($chartLeaderboard) / $itemsPerPage);
            $startIndex = ($currentPage - 1) * $itemsPerPage;
            $paginatedLeaderboard = array_slice($chartLeaderboard, $startIndex, $itemsPerPage);
        @endphp
        <div class="mb-12" id="leaderboard-section">
            @include('components.section-header', ['title' => '📊 Chart Leaderboard'])
            <div class="leaderboard-container" id="leaderboard-container">
                @include('dashboard._leaderboard-content', [
                    'paginatedLeaderboard' => $paginatedLeaderboard,
                    'startIndex' => $startIndex,
                    'currentPage' => $currentPage,
                    'totalPages' => $totalPages,
                    'userPlaylists' => $userPlaylists,
                ])
            </div>
        </div>
        
        <script>
            function changeLeaderboardPage(page) {
                const container = document.getElementById('leaderboard-container');
                if (!container) return;
                
                // Show loading state
                container.style.opacity = '0.5';
                container.style.pointerEvents = 'none';
                
                // Scroll leaderboard content to start (horizontal)
                const content = document.getElementById('leaderboard-content');
                if (content) {
                    content.scrollTo({ left: 0, behavior: 'smooth' });
                }
                
                // Fetch new content via AJAX
                fetch(`{{ route('dashboard.leaderboard') }}?page=${page}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update container content
                        container.innerHTML = data.content;
                        // Update URL without reload
                        const url = new URL(window.location);
                        url.searchParams.set('leaderboard_page', page);
                        window.history.pushState({}, '', url.toString());
                    } else {
                        alert('Failed to load leaderboard page.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load leaderboard page.');
                })
                .finally(() => {
                    // Remove loading state
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                });
            }
            
            // Event delegation for pagination buttons (works after AJAX update)
            document.addEventListener('click', function(e) {
                const button = e.target.closest('.leaderboard-page-btn');
                if (button) {
                    e.preventDefault();
                    const page = parseInt(button.getAttribute('data-page'));
                    if (page) {
                        changeLeaderboardPage(page);
                    }
                }
            });
        </script>
    @endif

    <!-- Genre Sections -->
    @if(!empty($genreSections))
        @foreach($genreSections as $genre => $songs)
            @if(!empty($songs) && count($songs) > 0)
                <div class="mb-12">
                    @include('components.section-header', ['title' => '🎸 ' . $genre])
                    <div class="music-section-scroll">
                        <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide">
                            @foreach($songs as $result)
                                <div class="flex-shrink-0 w-[180px]">
                                    @include('components.music-card', [
                                        'trackId' => $result['trackId'],
                                        'trackName' => $result['trackName'],
                                        'artistName' => $result['artistName'],
                                        'previewUrl' => $result['previewUrl'],
                                        'artworkUrl' => $result['artworkUrl'],
                                        'metadata' => [
                                            'album' => $result['collectionName'],
                                            'albumArtist' => $result['collectionArtistName'],
                                            'genre' => $result['primaryGenreName'],
                                            'duration' => $result['durationLabel'],
                                            'releaseYear' => $result['releaseYear'],
                                            'trackViewUrl' => $result['trackViewUrl'],
                                        ],
                                        'trackTimeMillis' => $result['trackTimeMillis'],
                                        'releaseDate' => $result['releaseDate'],
                                    ])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

    <!-- Recent Playlists -->
    @if($playlists->count() > 0)
        <div class="mb-12">
            @include('components.section-header', ['title' => 'Your Playlists', 'seeAllRoute' => route('playlists.index')])
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($playlists as $playlist)
                    @include('components.playlist-card', ['playlist' => $playlist])
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-12 bg-spotify-gray rounded-lg p-8 text-center border border-spotify-dark">
            <svg class="w-16 h-16 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
            </svg>
            <p class="text-spotify-text mb-4">You don't have any playlists yet.</p>
            <a href="{{ route('playlists.create') }}" class="inline-block bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-2 rounded-full transition-colors">
                Create Playlist
            </a>
        </div>
    @endif

    <!-- Recent Favorites -->
    @if($favorites->count() > 0)
        <div class="mb-12">
            @include('components.section-header', ['title' => 'Your Favorite Songs', 'seeAllRoute' => route('favorites.index')])
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($favorites as $favorite)
                    @include('components.music-card', [
                        'trackName' => $favorite->track_name,
                        'artistName' => $favorite->artist_name,
                        'previewUrl' => $favorite->preview_url,
                        'artworkUrl' => $favorite->artwork_url
                    ])
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-12 bg-spotify-gray rounded-lg p-8 text-center border border-spotify-dark">
            <svg class="w-16 h-16 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <p class="text-spotify-text mb-4">You don't have any favorite songs yet.</p>
            <a href="{{ route('music.search') }}" class="inline-block bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-2 rounded-full transition-colors">
                Search Music
            </a>
        </div>
    @endif
</div>
@endsection
