<?php

namespace Database\Seeders;

use App\Models\Ledger;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::factory()
            ->count(10)
            ->create()
            ->each(function ($txn) {
                if ($txn->from_account_id) {
                    Ledger::factory()->create([
                        'transaction_id' => $txn->id,
                        'account_id' => $txn->from_account_id,
                        'direction' => 'DEBIT',
                        'amount' => $txn->amount,
                    ]);
                }

                if ($txn->to_account_id) {
                    Ledger::factory()->create([
                        'transaction_id' => $txn->id,
                        'account_id' => $txn->to_account_id,
                        'direction' => 'CREDIT',
                        'amount' => $txn->amount,
                    ]);
                }
            });
    }
}
