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
        Schema::create('internship_resignations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('application_id')->nullable()->constrained('applications')->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->onDelete('set null');
            $table->string('reason_category'); // academic, health, relocation, personal, other
            $table->text('reason_details');
            $table->date('effective_date');
            $table->text('handover_notes')->nullable();
            $table->string('document_path')->nullable(); // Uploaded signed letter
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->text('review_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_resignations');
    }
};
