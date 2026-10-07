<?php

namespace App\Http\Resources;

use App\Services\Auth\Breach\BreachResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin BreachResult
 */
class BreachCheckResource extends JsonResource
{
    /**
     * Transform the breach check result into its canonical API shape.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->resource->toArray();
    }
}
