<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->foreignId('from_account_id')->nullable();
            $table->foreignId('to_account_id ')->nullable();
            $table->decimal('amount', 10, 2)
                ->default(0)->unsigned();
            $table->string('type')
                ->comment('deposit, withdrawal, transfer, fee');
            $table->string('status')
                ->comment('pending, completed, failed, reversed');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
