@props(['title', 'seeAllRoute' => null, 'seeAllText' => 'See all'])

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-white">{{ $title }}</h2>
    @if($seeAllRoute)
        <a href="{{ $seeAllRoute }}" class="text-spotify-text hover:text-white text-sm font-medium transition-colors">
            {{ $seeAllText }}
        </a>
    @endif
</div>

