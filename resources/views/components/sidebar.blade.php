<aside class="w-64 bg-black h-screen flex flex-col">
    <!-- Logo -->
    <div class="p-6">
        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-white hover:text-spotify-green transition-colors">
            Diltify
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 px-4 py-3 rounded-lg hover:bg-spotify-gray transition-colors {{ request()->routeIs('dashboard') ? 'bg-spotify-gray text-white' : 'text-spotify-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('music.search') }}" class="flex items-center space-x-4 px-4 py-3 rounded-lg hover:bg-spotify-gray transition-colors {{ request()->routeIs('music.*') ? 'bg-spotify-gray text-white' : 'text-spotify-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Search</span>
                </a>
            </li>
            <li>
                <a href="{{ route('playlists.index') }}" class="flex items-center space-x-4 px-4 py-3 rounded-lg hover:bg-spotify-gray transition-colors {{ request()->routeIs('playlists.*') ? 'bg-spotify-gray text-white' : 'text-spotify-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                    <span>Playlists</span>
                </a>
            </li>
            <li>
                <a href="{{ route('favorites.index') }}" class="flex items-center space-x-4 px-4 py-3 rounded-lg hover:bg-spotify-gray transition-colors {{ request()->routeIs('favorites.*') ? 'bg-spotify-gray text-white' : 'text-spotify-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span>Favorites</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Section -->
    <div class="p-4 border-t border-spotify-gray">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-spotify-green rounded-full flex items-center justify-center">
                    <span class="text-black font-bold">{{ strtoupper(substr(session('user_name', 'U'), 0, 1)) }}</span>
                </div>
                <div>
                    <div class="text-sm font-medium">{{ session('user_name', 'User') }}</div>
                    <div class="text-xs text-spotify-text">Free Plan</div>
                </div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full px-4 py-2 bg-spotify-gray hover:bg-spotify-dark rounded-lg text-sm transition-colors">
                Logout
            </button>
        </form>
    </div>
</aside>

