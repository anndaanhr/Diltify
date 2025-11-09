@extends('layouts.app')

@section('content')
@php
    $userPlaylists = $userPlaylists ?? collect();
@endphp
<div class="max-w-7xl mx-auto">
    <!-- Search Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-6">Search Music</h1>
        
        <form action="{{ route('music.search') }}" method="POST" class="mb-8" id="searchForm">
            @csrf
            <div class="relative">
                <input type="text" name="query" id="searchInput" value="{{ $query ?? '' }}" required
                    class="w-full px-6 py-4 bg-spotify-gray border border-gray-700 rounded-full text-white placeholder-spotify-text focus:outline-none focus:ring-2 focus:ring-spotify-green focus:border-transparent text-lg"
                    placeholder="Search for songs, artists, albums..." autocomplete="off">
                <button type="submit" class="absolute right-2 top-2 bg-spotify-green hover:bg-green-600 text-black p-3 rounded-full transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                
            </div>
        </form>
    </div>

    <!-- Results Section -->
    <div id="resultsSection">
        @if(isset($results) && count($results) > 0)
            <div>
                <h2 class="text-2xl font-bold text-white mb-6">
                    Search Results{{ $query ? ' for "' . $query . '"' : '' }}
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($results as $result)
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
                                'country' => $result['country'],
                                'trackViewUrl' => $result['trackViewUrl'],
                                'collectionViewUrl' => $result['collectionViewUrl'],
                            ],
                            'trackTimeMillis' => $result['trackTimeMillis'],
                            'releaseDate' => $result['releaseDate'],
                        ])
                    @endforeach
                </div>
            </div>
        @elseif(isset($query) && $query)
            <div class="text-center py-16">
                <svg class="w-24 h-24 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xl text-spotify-text mb-2">No results found</p>
                <p class="text-spotify-text">Try searching for a different song or artist.</p>
            </div>
        @else
            <!-- Start Searching Message / Suggestions will replace this -->
            <div id="startSearchingMessage" class="text-center py-16">
                <svg class="w-24 h-24 text-spotify-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-xl text-spotify-text mb-2">Start searching for music</p>
                <p class="text-spotify-text">Enter a song name, artist, or album in the search box above.</p>
            </div>
            
            <!-- Suggestions will be displayed here when user types -->
            <div id="suggestionsContainer" class="hidden">
                <h2 class="text-2xl font-bold text-white mb-6">Suggestions</h2>
                <div id="suggestionsGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    <!-- Suggestions will be inserted here -->
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    console.log('Search autocomplete script loaded');
    
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const startSearchingMessage = document.getElementById('startSearchingMessage');
    const suggestionsContainer = document.getElementById('suggestionsContainer');
    const suggestionsGrid = document.getElementById('suggestionsGrid');
    
    if (!searchInput) {
        console.error('Search input not found');
        return;
    }
    
    // Check if we're on the initial page (no search results yet)
    const isInitialPage = startSearchingMessage && suggestionsContainer;
    
    let debounceTimer;
    let currentRequest = null;

    // Debounce function
    function debounce(func, wait) {
        return function executedFunction(...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                func(...args);
            }, wait);
        };
    }

    // Fetch suggestions from API
    async function fetchSuggestions(query) {
        const trimmedQuery = query.trim();
        
        // Only show suggestions on initial page (when startSearchingMessage exists)
        if (!isInitialPage) {
            return;
        }
        
        if (trimmedQuery.length < 2) {
            showStartMessage();
            return;
        }

        console.log('Fetching suggestions for:', trimmedQuery);

        // Cancel previous request if still pending
        if (currentRequest) {
            currentRequest.abort();
        }

        try {
            // Show loading state
            if (startSearchingMessage) startSearchingMessage.style.display = 'none';
            if (suggestionsContainer) {
                suggestionsContainer.classList.remove('hidden');
                suggestionsGrid.innerHTML = '<div class="col-span-full text-center py-8 text-spotify-text">Loading suggestions...</div>';
            }

            // Create new AbortController for this request
            const controller = new AbortController();
            currentRequest = controller;

            const url = '{{ route("music.suggestions") }}?query=' + encodeURIComponent(trimmedQuery);
            console.log('Fetching from URL:', url);
            
            const response = await fetch(url, {
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });

            currentRequest = null;

            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error('Failed to fetch suggestions: ' + response.status);
            }

            const data = await response.json();
            console.log('Received data:', data);
            
            if (data.suggestions && data.suggestions.length > 0) {
                console.log('Displaying', data.suggestions.length, 'suggestions');
                displaySuggestions(data.suggestions);
            } else {
                console.log('No suggestions found');
                showStartMessage();
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Error fetching suggestions:', error);
                showStartMessage();
            } else {
                console.log('Request aborted');
            }
        }
    }

    // Display suggestions in grid (replacing start message)
    function displaySuggestions(suggestions) {
        if (!suggestions || suggestions.length === 0 || !suggestionsContainer || !suggestionsGrid) {
            showStartMessage();
            return;
        }

        // Hide start message, show suggestions container
        if (startSearchingMessage) startSearchingMessage.style.display = 'none';
        suggestionsContainer.classList.remove('hidden');
        suggestionsGrid.innerHTML = '';
        
        // Display up to 10 suggestions in grid format
        const limitedSuggestions = suggestions.slice(0, 10);
        
        limitedSuggestions.forEach((suggestion) => {
            // Create card wrapper
            const cardWrapper = document.createElement('div');
            cardWrapper.className = 'cursor-pointer';
            
            // Create card HTML similar to music-card component
            const trackName = escapeHtml(suggestion.trackName || 'Unknown');
            const artistName = escapeHtml(suggestion.artistName || 'Unknown');
            const artworkUrl = suggestion.artworkUrl || '';
            const trackId = suggestion.trackId || '';
            const previewUrl = suggestion.previewUrl || '';
            
            // Escape for JavaScript string
            const safeTrackName = trackName.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"');
            const safeArtistName = artistName.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"');
            const safePreviewUrl = previewUrl.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"');
            const safeArtworkUrl = artworkUrl.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"');
            // Build track URL
            const trackUrl = '/music/track/' + encodeURIComponent(trackId);
            
            // Create card element
            const cardDiv = document.createElement('div');
            cardDiv.className = 'group bg-spotify-gray/60 hover:bg-spotify-dark p-5 rounded-xl transition-all duration-200 shadow-lg shadow-black/10 backdrop-blur-sm border border-transparent hover:border-spotify-green/30';
            
            // Create link
            const link = document.createElement('a');
            link.href = trackUrl;
            
            // Artwork container
            const artworkContainer = document.createElement('div');
            artworkContainer.className = 'relative mb-4';
            
            const artworkDiv = document.createElement('div');
            artworkDiv.className = 'aspect-square bg-spotify-dark rounded-lg overflow-hidden relative';
            
            // Fallback SVG (create first so it can be referenced in onerror)
            const fallbackSvg = document.createElement('div');
            fallbackSvg.className = 'w-full h-full flex items-center justify-center';
            fallbackSvg.style.display = artworkUrl ? 'none' : 'flex';
            fallbackSvg.innerHTML = `
                <svg class="w-16 h-16 text-spotify-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                </svg>
            `;
            artworkDiv.appendChild(fallbackSvg);
            
            if (artworkUrl) {
                const img = document.createElement('img');
                img.src = artworkUrl;
                img.alt = trackName;
                img.className = 'w-full h-full object-cover';
                img.onerror = function() {
                    this.style.display = 'none';
                    fallbackSvg.style.display = 'flex';
                };
                artworkDiv.insertBefore(img, fallbackSvg);
            }
            
            // Play button overlay
            if (previewUrl) {
                const playOverlay = document.createElement('div');
                playOverlay.className = 'absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 flex items-center justify-center transition-all duration-200';
                playOverlay.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (typeof window.playPreview === 'function') {
                        window.playPreview(previewUrl, trackName, artistName, artworkUrl, {});
                    }
                };
                
                const playButton = document.createElement('button');
                playButton.className = 'opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all duration-200 w-12 h-12 bg-spotify-green rounded-full flex items-center justify-center hover:scale-110';
                playButton.innerHTML = `
                    <svg class="w-6 h-6 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                `;
                playOverlay.appendChild(playButton);
                artworkDiv.appendChild(playOverlay);
            }
            
            artworkContainer.appendChild(artworkDiv);
            
            // Track info
            const infoDiv = document.createElement('div');
            infoDiv.className = 'min-h-[50px]';
            
            const trackTitle = document.createElement('h3');
            trackTitle.className = 'font-semibold text-white truncate mb-1';
            trackTitle.textContent = trackName;
            trackTitle.title = trackName;
            
            const artistP = document.createElement('p');
            artistP.className = 'text-sm text-spotify-text truncate';
            artistP.textContent = artistName;
            artistP.title = artistName;
            
            infoDiv.appendChild(trackTitle);
            infoDiv.appendChild(artistP);
            
            // Assemble
            link.appendChild(artworkContainer);
            link.appendChild(infoDiv);
            cardDiv.appendChild(link);
            cardWrapper.appendChild(cardDiv);
            
            suggestionsGrid.appendChild(cardWrapper);
        });
    }

    // Show start message (hide suggestions)
    function showStartMessage() {
        if (startSearchingMessage) {
            startSearchingMessage.style.display = 'block';
        }
        if (suggestionsContainer) {
            suggestionsContainer.classList.add('hidden');
        }
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Debounced search function - reduced delay for more responsive feel
    const debouncedFetchSuggestions = debounce(fetchSuggestions, 250);

    // Event listeners
    console.log('Setting up event listeners');
    
    // Only setup live suggestions if we're on the initial page
    if (isInitialPage) {
        // Input event - trigger on every keystroke (LIVE SEARCH)
        searchInput.addEventListener('input', function(e) {
            const query = this.value;
            console.log('Input event triggered, query:', query);
            if (query.length >= 2) {
                debouncedFetchSuggestions(query);
            } else {
                showStartMessage();
            }
        });

        // Focus event - show suggestions if there's already text
        searchInput.addEventListener('focus', function(e) {
            const query = this.value.trim();
            console.log('Focus event triggered, query:', query);
            if (query.length >= 2) {
                debouncedFetchSuggestions(query);
            }
        });

        // Clear suggestions when input is cleared
        searchInput.addEventListener('blur', function(e) {
            // Small delay to allow clicks on suggestions
            setTimeout(function() {
                if (!searchInput.value || searchInput.value.trim().length < 2) {
                    showStartMessage();
                }
            }, 200);
        });
    }

    // Hide suggestions on form submit
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            if (suggestionsContainer) {
                suggestionsContainer.classList.add('hidden');
            }
        });
    }
    
    console.log('Autocomplete initialized successfully');
});
</script>
@endpush
@endsection

