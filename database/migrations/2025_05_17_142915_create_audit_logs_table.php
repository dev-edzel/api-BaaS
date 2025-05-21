<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('initiator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->index('initiator_id');
            $table->string('initiator_username');
            $table->bigInteger('initiator_role');
            $table->string('action');
            $table->text('details')->nullable();
            $table->string('ip_address');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
