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
        Schema::create('account_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id');
            $table->foreignId('fee_id');
            $table->foreignId('transaction_id');
            $table->timestamp('charged_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_fees');
    }
};
