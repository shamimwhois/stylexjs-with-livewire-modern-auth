<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seller>
 */
class SellerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'badge' => fake()->randomElement(['Gold', 'Silver', 'Bronze', null]),
            'image' => fake()->imageUrl(200, 200),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
