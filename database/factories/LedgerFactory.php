<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ledger>
 */
class LedgerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'transaction_id' => null,
            'account_id' => fake()->numberBetween(1, 50),
            'direction' => fake()->randomElement(['CREDIT', 'DEBIT']),
            'amount' => fake()->randomFloat(2, 100, 10000),
        ];
    }
}
