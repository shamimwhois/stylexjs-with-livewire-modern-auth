<?php

namespace Database\Factories;

use App\Models\SocialProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialProfile>
 */
class SocialProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'input_type' => fake()->randomElement(['facebook', 'whatsapp', 'telegram', 'website', 'x']),
            'url' => fake()->url(),
            'username' => fake()->userName(),
            'avatar' => fake()->imageUrl(64, 64),
            'bio' => fake()->paragraph(),
        ];
    }
}
