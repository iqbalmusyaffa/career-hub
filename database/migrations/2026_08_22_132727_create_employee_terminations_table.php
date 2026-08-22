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
        Schema::create('employee_terminations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('document_number');
            $table->string('document_type'); // recommendation_letter, paklaring_letter, phk_letter, contract_expired
            $table->string('employee_name');
            $table->string('job_title');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason_or_recommendation_notes')->nullable();
            $table->string('severance_compensation')->nullable();
            $table->string('hr_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->date('issued_at');
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_terminations');
    }
};
