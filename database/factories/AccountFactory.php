<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'account_number' => fake()->randomNumber(12),
            'account_type' => fake()->randomElement(['CURRENT', 'SAVINGS']),
            'status' => fake()->randomElement(['ACTIVE', 'BLOCKED', 'CLOSED']),
            'balance' => fake()->randomFloat(2, 100, 10000),
            'currency' => fake()->randomElement(['NGN', 'USD', 'EUR', 'PHP']),
        ];
    }
}
