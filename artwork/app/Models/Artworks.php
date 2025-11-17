<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artworks extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'file_path',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that created this artwork.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of this artwork.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class);
    }

    // === PERBAIKI SEMUA FUNGSI DI BAWAH INI ===

    /**
     * Get all comments on this artwork.
     */
    public function comments(): HasMany
    {
        // Tambahkan 'artwork_id'
        return $this->hasMany(Comments::class, 'artwork_id');
    }

    /**
     * Get all likes on this artwork.
     */
    public function likes(): HasMany
    {
        // Tambahkan 'artwork_id'
        return $this->hasMany(Likes::class, 'artwork_id');
    }

    /**
     * Get all favorites for this artwork.
     */
    public function favorites(): HasMany
    {
        // Tambahkan 'artwork_id'
        return $this->hasMany(Favorites::class, 'artwork_id');
    }

    /**
     * Get all challenge submissions for this artwork.
     */
    public function challengeSubmissions(): HasMany
    {
        // Tambahkan 'artwork_id'
        return $this->hasMany(ChallengeSubmission::class, 'artwork_id');
    }

    /**
     * Get all reports for this artwork.
     */
    public function reports(): HasMany
    {
        // Tambahkan 'artwork_id'
        return $this->hasMany(Reports::class, 'artwork_id');
    }
}
