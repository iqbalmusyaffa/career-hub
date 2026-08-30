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
        Schema::create('internship_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
            
            // 5 Competency Indicators (Scale 1 - 100)
            $table->integer('discipline_score')->default(85);      // Kedisiplinan & Presensi (20%)
            $table->integer('initiative_score')->default(85);      // Inisiatif & Keaktifan (20%)
            $table->integer('work_quality_score')->default(85);    // Kualitas Hasil Pekerjaan (25%)
            $table->integer('teamwork_score')->default(85);        // Kerjasama Tim & Komunikasi (20%)
            $table->integer('problem_solving_score')->default(85);  // Problem Solving & Adaptasi (15%)
            
            $table->decimal('final_score', 5, 2)->default(85.00);
            $table->string('final_grade')->default('A');            // A, B, C, D
            
            $table->text('feedback_summary')->nullable();           // Catatan Evaluasi Akhir
            $table->string('recommendation')->default('recommended'); // highly_recommended, recommended, neutral, not_recommended
            
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_evaluations');
    }
};
