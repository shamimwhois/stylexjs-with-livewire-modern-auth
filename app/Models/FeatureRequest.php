<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'title', 'description', 'status'])]
class FeatureRequest extends Model
{
    use HasFactory;

    /**
     * Get the user that created the feature request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
