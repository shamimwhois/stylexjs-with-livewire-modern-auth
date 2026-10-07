<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'name', 'hosting_ip', 'domain', 'license_key', 'license_api_key', 'status', 'type', 'description'])]
class Licence extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the licence.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
