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
        Schema::create('internship_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('certificate_number');
            $table->string('participant_name');
            $table->string('institution_name')->nullable();
            $table->string('job_title');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('performance_grade');
            $table->string('mentor_name')->nullable();
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
        Schema::dropIfExists('internship_certificates');
    }
};
