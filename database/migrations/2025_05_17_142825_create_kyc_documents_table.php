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
        Schema::create('kyc_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('document_type')
                ->comment('passport, utility_bill, selfie, etc');
            $table->string('document_path');
            $table->string('status')
                ->comment('pending, approved, rejected');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('uploaded_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_documents');
    }
};
