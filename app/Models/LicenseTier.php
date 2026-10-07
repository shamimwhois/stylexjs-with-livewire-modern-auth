<?php

namespace App\Models;

use Database\Factories\LicenseTierFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'name', 'slug', 'description', 'sort_order', 'price_monthly', 'price_annual', 'price_lifetime', 'setup_fee', 'features', 'limits', 'status'])]
class LicenseTier extends Model
{
    /** @use HasFactory<LicenseTierFactory> */
    use HasFactory, HasUuids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'price_monthly' => 'decimal:2',
            'price_annual' => 'decimal:2',
            'price_lifetime' => 'decimal:2',
            'setup_fee' => 'decimal:2',
            'features' => 'array',
            'limits' => 'array',
        ];
    }

    /**
     * The product this tier belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
