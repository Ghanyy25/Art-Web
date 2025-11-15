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

    /**
     * Get all comments on this artwork.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class);
    }

    /**
     * Get all likes on this artwork.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Likes::class);
    }

    /**
     * Get all favorites for this artwork.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorites::class);
    }

    /**
     * Get all challenge submissions for this artwork.
     */
    public function challengeSubmissions(): HasMany
    {
        return $this->hasMany(ChallengeSubmission::class);
    }

    /**
     * Get all reports for this artwork.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Reports::class);
    }
}
