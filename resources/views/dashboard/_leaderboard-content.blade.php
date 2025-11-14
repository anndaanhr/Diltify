<!-- Leaderboard Content -->
<div class="music-section-scroll">
    <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide" id="leaderboard-content">
        @foreach($paginatedLeaderboard as $index => $result)
            <div class="flex-shrink-0 w-[180px]">
                @include('components.leaderboard-item', [
                    'rank' => $startIndex + $index + 1,
                    'result' => $result
                ])
            </div>
        @endforeach
    </div>
</div>

<!-- Pagination -->
@if($totalPages > 1)
    <div class="flex justify-center items-center gap-2 pt-6" id="leaderboard-pagination">
        @if($currentPage > 1)
            <button data-page="{{ $currentPage - 1 }}" class="leaderboard-page-btn px-3 py-2 bg-spotify-dark hover:bg-spotify-gray rounded-lg text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
        @endif
        
        @for($i = 1; $i <= 5; $i++)
            @if($i <= $totalPages)
                <button data-page="{{ $i }}" 
                   class="leaderboard-page-btn px-4 py-2 rounded-lg transition-colors {{ $i == $currentPage ? 'bg-spotify-green text-black font-bold' : 'bg-spotify-dark hover:bg-spotify-gray text-white' }}">
                    {{ $i }}
                </button>
            @endif
        @endfor
        
        @if($currentPage < $totalPages)
            <button data-page="{{ $currentPage + 1 }}" class="leaderboard-page-btn px-3 py-2 bg-spotify-dark hover:bg-spotify-gray rounded-lg text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @endif
    </div>
@endif

