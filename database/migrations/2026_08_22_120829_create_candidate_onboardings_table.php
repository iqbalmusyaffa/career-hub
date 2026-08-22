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
        Schema::create('candidate_onboardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_account_holder');
            $table->string('npwp_number')->nullable();
            $table->string('npwp_doc_path')->nullable();
            $table->string('bpjs_kesehatan_number')->nullable();
            $table->string('bpjs_kesehatan_doc_path')->nullable();
            $table->string('bpjs_ketenagakerjaan_number')->nullable();
            $table->string('bpjs_ketenagakerjaan_doc_path')->nullable();
            $table->string('family_card_number')->nullable();
            $table->string('family_card_doc_path')->nullable();
            $table->string('additional_doc_path')->nullable();
            $table->text('notes')->nullable();
            $table->string('verification_status')->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_onboardings');
    }
};
