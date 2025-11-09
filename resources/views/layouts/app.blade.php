<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Diltify - Music Player' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-spotify-black text-white min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto scrollbar-dark">
            <div class="p-8">
                @include('components.alert')
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Audio Player (custom mini player) -->
    <div id="audioPlayer" class="hidden audio-player fixed bottom-0 left-0 right-0 px-8 py-4">
        <div class="player-shell max-w-7xl mx-auto flex items-center space-x-6">
            <div class="flex items-center space-x-4 flex-1 min-w-0">
                <div id="playerArtwork" class="w-16 h-16 rounded-xl bg-spotify-dark bg-center bg-cover shadow-inner"></div>
                <div class="min-w-0">
                    <div id="playerTrackName" class="text-sm font-semibold truncate"></div>
                    <div id="playerArtistName" class="text-xs text-spotify-text truncate"></div>
                    <div id="playerMeta" class="text-[11px] text-spotify-text/80 mt-1 truncate"></div>
                </div>
            </div>
            <div class="flex flex-col items-center flex-1 space-y-2">
                <div class="flex items-center space-x-3">
                    <button id="playerRewind" class="player-icon-button" aria-label="Rewind 10 seconds">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l-7-7 7-7v14zm7 0l-7-7 7-7v14z"></path>
                        </svg>
                    </button>
                    <button id="playerPlayPause" class="player-play-button" aria-label="Play">
                        <svg class="w-4 h-4 play-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <svg class="w-4 h-4 pause-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6"></path>
                        </svg>
                    </button>
                    <button id="playerForward" class="player-icon-button" aria-label="Forward 10 seconds">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5l7 7-7 7V5zm-7 0l7 7-7 7V5z"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center space-x-3 w-full">
                    <span id="playerCurrentTime" class="text-[11px] text-spotify-text w-10 text-right">0:00</span>
                    <input id="playerProgress" type="range" min="0" max="100" value="0" class="flex-1 player-progress">
                    <span id="playerDuration" class="text-[11px] text-spotify-text w-10">0:30</span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button id="playerMute" class="player-icon-button" aria-label="Mute">
                    <svg class="w-4 h-4 volume-on" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5l-6 6 6 6V5zm4 0v14m4-10v6"></path>
                    </svg>
                    <svg class="w-4 h-4 volume-off hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9l6 6m0-6l-6 6m10 2V3m-4 4v10"></path>
                    </svg>
                </button>
                <input id="playerVolume" type="range" min="0" max="1" step="0.05" value="0.8" class="w-24 player-volume">
                <button id="playerClose" class="player-icon-button" aria-label="Close player">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <audio id="audioElement" class="hidden"></audio>
    </div>

    @stack('scripts')
</body>
</html>

