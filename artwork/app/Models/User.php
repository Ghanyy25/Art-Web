<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'profile_picture',
        'bio',
        'external_links',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'external_links' => 'json',
        ];
    }

    /**
     * Get all artworks created by this user.
     */
    public function artworks(): HasMany
    {
        return $this->hasMany(Artworks::class);
    }

    /**
     * Get all comments made by this user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class);
    }

    /**
     * Get all likes made by this user.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Likes::class);
    }

    /**
     * Get all favorites created by this user.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorites::class);
    }

    /**
     * Get all challenge submissions from this user.
     */
    public function challengeSubmissions(): HasMany
    {
        return $this->hasMany(ChallengeSubmission::class);
    }

    /**
     * Get all challenges created by this user.
     */
    public function challenges(): HasMany
    {
        return $this->hasMany(Challenges::class);
    }

    /**
     * Get all reports made by this user.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Reports::class);
    }

    // Tambahkan relasi ini di dalam class User
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    // Helper untuk cek status follow
    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }
}
