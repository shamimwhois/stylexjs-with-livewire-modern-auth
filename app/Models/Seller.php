<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'social_profile_id', 'name', 'badge', 'image', 'email'])]
class Seller extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the seller.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the social profile for the seller.
     */
    public function socialProfile(): BelongsTo
    {
        return $this->belongsTo(SocialProfile::class);
    }
}
