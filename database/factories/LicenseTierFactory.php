<?php

namespace Database\Factories;

use App\Models\LicenseTier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LicenseTier>
 */
class LicenseTierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'slug' => fn (array $attributes) => Str::slug($attributes['name']),
            'description' => fake()->sentence(),
            'sort_order' => 0,
            'price_monthly' => fake()->randomFloat(2, 5, 100),
            'price_annual' => fake()->randomFloat(2, 5, 100),
            'price_lifetime' => fake()->randomFloat(2, 100, 1000),
            'setup_fee' => 0,
            'features' => fake()->words(3),
            'limits' => ['seats' => fake()->randomDigitNotNull()],
            'status' => 'active',
        ];
    }
}
