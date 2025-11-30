<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reports extends Model
{
    protected $fillable = [
        'reporter_user_id',
        'artwork_id',
        'comment_id',
        'reason',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who reported.
     */
    public function reporterUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    /**
     * Get the artwork that was reported.
     */
    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artworks::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comments::class, 'comment_id');
    }
}
