<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeSubmission extends Model
{
    protected $fillable = [
        'challenge_id',
        'artwork_id',
        'user_id',
        'placement',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the challenge for this submission.
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenges::class);
    }

    /**
     * Get the artwork for this submission.
     */
    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artworks::class);
    }

    /**
     * Get the user who submitted.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
