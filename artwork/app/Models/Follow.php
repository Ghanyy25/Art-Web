<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Follow extends Model
{
    protected $fillable = [
        'follower_id',
        'following_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who follow.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Get the user who is followed.
     */
    public function followedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
