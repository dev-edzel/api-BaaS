<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
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
            'linked_account_id' => Account::factory(),
            'card_number' => fake()->creditCardNumber(),
            'status' => fake()->randomElement(['ACTIVE', 'BLOCKED', 'EXPIRED']),
            'expiry_date' => fake()->date()
        ];
    }
}
