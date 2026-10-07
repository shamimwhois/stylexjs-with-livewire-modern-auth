<?php

namespace App\Models;

use Database\Factories\LoginDeviceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $user_id
 * @property string $fingerprint
 * @property ?string $ip_address
 * @property ?string $user_agent
 * @property Carbon $last_seen_at
 */
#[Fillable(['user_id', 'fingerprint', 'ip_address', 'user_agent', 'last_seen_at'])]
class LoginDevice extends Model
{
    /** @use HasFactory<LoginDeviceFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * The user this known device belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
