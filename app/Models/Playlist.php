<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class Playlist extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'name',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the encrypted name attribute.
     */
    public function getNameAttribute($value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Set the encrypted name attribute.
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = Crypt::encryptString($value);
    }

    /**
     * Get the user that owns the playlist.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tracks in the playlist.
     */
    public function tracks()
    {
        return $this->hasMany(PlaylistTrack::class);
    }

    /**
     * Scope a query to only include playlists for a specific user.
     */
    public function scopeByUser($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }
}

