<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['DEPOSIT', 'WITHDRAWAL', 'TRANSFER', 'FEE']);

        $fromAccountId = null;
        $toAccountId = null;

        if ($type === 'DEPOSIT') {
            $fromAccountId = fake()->numberBetween(1, 10);
        }

        if ($type === 'TRANSFER') {
            $fromAccountId = fake()->numberBetween(1, 10);
            $toAccountId = fake()->numberBetween(1, 10);
        }

        if (in_array($type, ['WITHDRAWAL', 'FEE'])) {
            $fromAccountId = fake()->numberBetween(1, 10);
        }

        return [
            'reference_no' => fake()->unique()->numerify('DIGIBANK#######'),
            'from_account_id' => $fromAccountId,
            'to_account_id' => $toAccountId,
            'amount' => fake()->randomFloat(2, 100, 10000),
            'type' => $type,
            'status' => fake()->randomElement(['PENDING', 'SUCCESS', 'FAILED', 'REVERSED']),
            'description' => fake()->word(),
        ];
    }
}
