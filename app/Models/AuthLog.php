<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property ?int $user_id
 * @property string $event
 * @property ?string $ip_address
 * @property ?string $user_agent
 * @property ?array $metadata
 * @property Carbon $created_at
 */
#[Fillable(['user_id', 'event', 'ip_address', 'user_agent', 'metadata'])]
class AuthLog extends Model
{
    /**
     * The log rows are append-only; created_at is set by the database.
     */
    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * The user this log entry belongs to, when authenticated.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
