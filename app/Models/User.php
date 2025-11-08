<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
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
     * Get the encrypted email attribute.
     */
    public function getEmailAttribute($value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Set the encrypted email attribute.
     */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = Crypt::encryptString($value);
    }

    /**
     * Get all playlists for the user.
     */
    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    /**
     * Get all favorites for the user.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Find user by email (handling encryption).
     */
    public static function whereEmail(string $email)
    {
        $users = static::all();
        foreach ($users as $user) {
            if ($user->email === $email) {
                return $user;
            }
        }
        return null;
    }
}
