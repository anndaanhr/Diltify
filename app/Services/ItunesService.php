<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ItunesService
{
    private const ITUNES_SEARCH_URL = 'https://itunes.apple.com/search';
    private const CACHE_TTL = 3600; // 1 hour cache

    /**
     * Format search results to consistent format.
     */
    public function formatSearchResults(array $items): array
    {
        return array_map(function ($item) {
            $trackTimeMillis = $item['trackTimeMillis'] ?? null;
            $releaseDate = $item['releaseDate'] ?? null;

            return [
                'trackId' => $item['trackId'] ?? null,
                'trackName' => $item['trackName'] ?? 'Unknown',
                'artistName' => $item['artistName'] ?? 'Unknown',
                'previewUrl' => $item['previewUrl'] ?? null,
                'artworkUrl' => $item['artworkUrl100'] ?? $item['artworkUrl60'] ?? null,
                'collectionName' => $item['collectionName'] ?? null,
                'collectionArtistName' => $item['collectionArtistName'] ?? $item['artistName'] ?? null,
                'primaryGenreName' => $item['primaryGenreName'] ?? null,
                'trackTimeMillis' => $trackTimeMillis,
                'durationLabel' => $trackTimeMillis ? gmdate('i:s', (int) ($trackTimeMillis / 1000)) : null,
                'releaseDate' => $releaseDate,
                'releaseYear' => $releaseDate ? date('Y', strtotime($releaseDate)) : null,
                'country' => $item['country'] ?? null,
                'currency' => $item['currency'] ?? null,
                'trackViewUrl' => $item['trackViewUrl'] ?? null,
                'collectionViewUrl' => $item['collectionViewUrl'] ?? null,
            ];
        }, $items);
    }

    /**
     * Search iTunes API with given parameters.
     */
    private function search(array $params, int $limit = 10): array
    {
        $cacheKey = 'itunes_search_' . md5(json_encode($params) . $limit);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($params, $limit) {
            try {
                $params['media'] = 'music';
                $params['limit'] = $limit;

                $response = Http::timeout(10)->get(self::ITUNES_SEARCH_URL, $params);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['results']) && is_array($data['results'])) {
                        return $this->formatSearchResults($data['results']);
                    }
                }
            } catch (\Exception $e) {
                // Log error if needed
            }

            return [];
        });
    }

    /**
     * Get top charts (using popular search terms).
     */
    public function getTopCharts(int $limit = 10): array
    {
        // Try popular artists/songs that are typically in charts
        $popularQueries = ['taylor swift', 'ed sheeran', 'the weeknd', 'drake', 'ariana grande'];
        $randomQuery = $popularQueries[array_rand($popularQueries)];

        return $this->search(['term' => $randomQuery], $limit);
    }

    /**
     * Get hot today songs (recent popular releases).
     */
    public function getHotToday(int $limit = 10): array
    {
        // Use diverse popular artists and songs for variety
        $hotQueries = [
            'taylor swift', 'ariana grande', 'the weeknd', 'drake', 'billie eilish',
            'bad bunny', 'the weeknd blinding lights', 'harry styles', 'olivia rodrigo',
            'doja cat', 'sza', 'lizzo', 'miley cyrus', 'sam smith'
        ];
        
        // Get from multiple different artists for variety
        $allResults = [];
        $queriesToUse = array_slice($hotQueries, 0, 4);
        $limitPerQuery = max(3, (int) ceil($limit / count($queriesToUse)));
        
        foreach ($queriesToUse as $query) {
            $results = $this->search(['term' => $query], $limitPerQuery);
            $allResults = array_merge($allResults, $results);
        }

        // Remove duplicates and shuffle
        $uniqueResults = [];
        $seenIds = [];
        foreach ($allResults as $result) {
            if ($result['trackId'] && !in_array($result['trackId'], $seenIds)) {
                $uniqueResults[] = $result;
                $seenIds[] = $result['trackId'];
            }
        }

        shuffle($uniqueResults);
        return array_slice($uniqueResults, 0, $limit);
    }

    /**
     * Get hits (popular songs).
     */
    public function getHits(int $limit = 10): array
    {
        // Use diverse hit songs for variety
        $hitQueries = [
            'shape of you', 'blinding lights', 'watermelon sugar', 'good 4 u', 'levitating',
            'flowers miley cyrus', 'as it was harry styles', 'unholy sam smith', 'calm down rema',
            'anti-hero taylor swift', 'about damn time lizzo', 'stay the kid laroi', 'heat waves glass animals'
        ];
        
        // Get from multiple different hit songs
        $allResults = [];
        $queriesToUse = array_slice($hitQueries, 0, 4);
        $limitPerQuery = max(3, (int) ceil($limit / count($queriesToUse)));
        
        foreach ($queriesToUse as $query) {
            $results = $this->search(['term' => $query], $limitPerQuery);
            $allResults = array_merge($allResults, $results);
        }

        // Remove duplicates and shuffle
        $uniqueResults = [];
        $seenIds = [];
        foreach ($allResults as $result) {
            if ($result['trackId'] && !in_array($result['trackId'], $seenIds)) {
                $uniqueResults[] = $result;
                $seenIds[] = $result['trackId'];
            }
        }

        shuffle($uniqueResults);
        return array_slice($uniqueResults, 0, $limit);
    }

    /**
     * Get recommendations (curated mix).
     */
    public function getRecommendations(int $limit = 10): array
    {
        // Mix of diverse popular artists for variety
        $recommendationQueries = [
            'taylor swift', 'the weeknd', 'ariana grande', 'drake', 
            'billie eilish', 'post malone', 'dua lipa', 'harry styles',
            'bad bunny', 'sza', 'lizzo', 'olivia rodrigo', 'doja cat',
            'bruno mars', 'ed sheeran', 'coldplay', 'imagine dragons'
        ];
        
        // Get songs from multiple diverse artists
        $allResults = [];
        $queriesToUse = array_slice($recommendationQueries, 0, 5);
        $limitPerQuery = max(2, (int) ceil($limit / count($queriesToUse)));
        
        foreach ($queriesToUse as $query) {
            $results = $this->search(['term' => $query], $limitPerQuery);
            $allResults = array_merge($allResults, $results);
        }

        // Remove duplicates and shuffle
        $uniqueResults = [];
        $seenIds = [];
        foreach ($allResults as $result) {
            if ($result['trackId'] && !in_array($result['trackId'], $seenIds)) {
                $uniqueResults[] = $result;
                $seenIds[] = $result['trackId'];
            }
        }

        shuffle($uniqueResults);
        return array_slice($uniqueResults, 0, $limit);
    }

    /**
     * Get songs by genre.
     */
    public function getGenreSongs(string $genre, int $limit = 10): array
    {
        // Popular artists/terms for each genre - expanded for more variety
        $genreQueries = [
            'Pop' => ['taylor swift', 'ariana grande', 'dua lipa', 'harry styles', 'olivia rodrigo', 'doja cat', 'lizzo', 'sam smith'],
            'Rock' => ['imagine dragons', 'coldplay', 'the killers', 'foo fighters', 'red hot chili peppers', 'linkin park', 'green day', 'fall out boy'],
            'Hip-Hop' => ['drake', 'kendrick lamar', 'travis scott', 'post malone', 'kanye west', 'eminem', 'j. cole', '21 savage'],
            'R&B' => ['the weeknd', 'bruno mars', 'sza', 'daniel caesar', 'usher', 'chris brown', 'the weeknd', 'h.e.r.'],
            'Country' => ['luke combs', 'morgan wallen', 'kacey musgraves', 'maren morris', 'kane brown', 'blake shelton', 'carrie underwood', 'jason aldean'],
            'Electronic' => ['the chainsmokers', 'calvin harris', 'marshmello', 'zedd', 'avicii', 'skrillex', 'deadmau5', 'tiësto'],
            'Jazz' => ['norah jones', 'diana krall', 'michael bublé', 'jamie cullum', 'diana krall', 'john coltrane', 'miles davis', 'ella fitzgerald'],
            'Classical' => ['beethoven', 'mozart', 'bach', 'chopin', 'vivaldi', 'tchaikovsky', 'debussy', 'handel'],
        ];

        $queries = $genreQueries[$genre] ?? ['music'];
        
        // Use multiple queries from the genre for variety
        $allResults = [];
        $queriesToUse = array_slice($queries, 0, 4);
        $limitPerQuery = max(3, (int) ceil($limit / count($queriesToUse)));
        
        foreach ($queriesToUse as $query) {
            $results = $this->search(['term' => $query], $limitPerQuery * 2);
            $allResults = array_merge($allResults, $results);
        }
        
        // Filter by genre if available
        $filtered = array_filter($allResults, function ($item) use ($genre) {
            return strtolower($item['primaryGenreName'] ?? '') === strtolower($genre);
        });

        // Remove duplicates
        $uniqueResults = [];
        $seenIds = [];
        foreach ($filtered as $result) {
            if ($result['trackId'] && !in_array($result['trackId'], $seenIds)) {
                $uniqueResults[] = $result;
                $seenIds[] = $result['trackId'];
            }
        }

        if (count($uniqueResults) >= $limit) {
            shuffle($uniqueResults);
            return array_slice($uniqueResults, 0, $limit);
        }

        // If not enough genre-specific results, use all results
        $allUnique = [];
        $allSeenIds = [];
        foreach ($allResults as $result) {
            if ($result['trackId'] && !in_array($result['trackId'], $allSeenIds)) {
                $allUnique[] = $result;
                $allSeenIds[] = $result['trackId'];
            }
        }

        shuffle($allUnique);
        return array_slice($allUnique, 0, $limit);
    }

    /**
     * Get chart leaderboard (top songs overall).
     */
    public function getChartLeaderboard(int $limit = 50): array
    {
        // Use diverse chart-topping artists and songs
        $chartQueries = [
            'taylor swift', 'bad bunny', 'the weeknd', 'drake', 'harry styles',
            'ariana grande', 'billie eilish', 'post malone', 'olivia rodrigo',
            'sza', 'morgan wallen', 'luke combs', 'doja cat', 'lizzo',
            'ed sheeran', 'bruno mars', 'justin bieber', 'the chainsmokers',
            'imagine dragons', 'coldplay', 'maroon 5', 'one republic'
        ];

        $allResults = [];
        $queriesToUse = array_slice($chartQueries, 0, 10);
        $limitPerQuery = max(5, (int) ceil($limit / count($queriesToUse)));
        
        foreach ($queriesToUse as $query) {
            $results = $this->search(['term' => $query], $limitPerQuery);
            $allResults = array_merge($allResults, $results);
        }

        // Remove duplicates by trackId
        $uniqueResults = [];
        $seenIds = [];
        foreach ($allResults as $result) {
            if ($result['trackId'] && !in_array($result['trackId'], $seenIds)) {
                $uniqueResults[] = $result;
                $seenIds[] = $result['trackId'];
            }
        }

        shuffle($uniqueResults);
        return array_slice($uniqueResults, 0, $limit);
    }
}

