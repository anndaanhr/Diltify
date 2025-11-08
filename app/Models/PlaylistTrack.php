<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PlaylistTrack extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'playlist_id',
        'track_id',
        'track_name',
        'artist_name',
        'collection_name',
        'collection_artist_name',
        'primary_genre_name',
        'track_time_millis',
        'release_date',
        'preview_url',
        'artwork_url',
    ];

    protected $casts = [
        'release_date' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
}
