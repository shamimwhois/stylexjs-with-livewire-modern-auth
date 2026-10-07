<?php

namespace Database\Factories;

use App\Models\Licence;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Licence>
 */
class LicenceFactory extends Factory
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
            'domain' => fake()->unique()->domainName(),
            'license_key' => Str::upper(Str::random(24)),
            'license_api_key' => Str::upper(Str::random(32)),
            'status' => fake()->randomElement(['active', 'suspended', 'expired']),
            'type' => fake()->randomElement(['standard', 'premium', 'lifetime']),
            'description' => fake()->sentence(),
        ];
    }
}
