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
        Schema::create('job_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_postings')->onDelete('cascade');
            $table->string('title');
            $table->string('category')->default('psikotes');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->integer('passing_score')->default(70);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_test_id')->constrained('job_tests')->onDelete('cascade');
            $table->text('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->enum('correct_option', ['a', 'b', 'c', 'd']);
            $table->integer('points')->default(10);
            $table->timestamps();
        });

        Schema::create('candidate_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained('job_postings')->onDelete('cascade');
            $table->foreignId('job_test_id')->constrained('job_tests')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->boolean('passed')->default(false);
            $table->json('answers')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_test_results');
        Schema::dropIfExists('test_questions');
        Schema::dropIfExists('job_tests');
    }
};
