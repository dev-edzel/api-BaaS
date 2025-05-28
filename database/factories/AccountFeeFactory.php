<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Fee;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountFee>
 */
class AccountFeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'fee_id' => Fee::factory(),
            'transaction_id' => Transaction::factory(),
            'charged_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
