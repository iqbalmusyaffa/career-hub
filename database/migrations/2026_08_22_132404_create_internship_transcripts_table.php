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
        Schema::create('internship_transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('transcript_number');
            $table->string('participant_name');
            $table->string('student_id_number')->nullable();
            $table->string('institution_name')->nullable();
            $table->string('job_title');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('score_discipline', 5, 2)->default(0);
            $table->decimal('score_technical', 5, 2)->default(0);
            $table->decimal('score_communication', 5, 2)->default(0);
            $table->decimal('score_problem_solving', 5, 2)->default(0);
            $table->decimal('score_ethics', 5, 2)->default(0);
            $table->decimal('final_score', 5, 2)->default(0);
            $table->string('grade_letter');
            $table->text('mentor_notes')->nullable();
            $table->string('mentor_name')->nullable();
            $table->string('mentor_phone')->nullable();
            $table->string('mentor_email')->nullable();
            $table->string('hr_name')->nullable();
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
        Schema::dropIfExists('internship_transcripts');
    }
};
