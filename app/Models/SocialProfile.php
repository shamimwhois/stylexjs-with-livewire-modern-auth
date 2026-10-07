<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'input_type', 'url', 'username', 'avatar', 'bio'])]
class SocialProfile extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the social profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sellers for the social profile.
     */
    public function sellers(): HasMany
    {
        return $this->hasMany(Seller::class);
    }
}
