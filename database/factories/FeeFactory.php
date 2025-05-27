<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fee>
 */
class FeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'amount' => fake()->randomFloat(2, 100, 10000),
            'is_percentage' => fake()->boolean(),
            'applies_to' => fake()->randomElement(['ACCOUNT_TYPE', 'TRANSACTION_TYPE']),
        ];
    }
}
