<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenges extends Model
{
    protected $fillable = [
        'curator_id',
        'title',
        'description',
        'rules',
        'prizes',
        'banner_image',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the curator that created this challenge.
     */
    public function curator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'curator_id');
    }

    /**
     * Get all submissions for this challenge.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ChallengeSubmission::class);
    }
}
