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
        Schema::create('internship_curriculums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained('job_postings')->onDelete('cascade');
            $table->string('batch')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('curriculum_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_curriculum_id')->constrained('internship_curriculums')->onDelete('cascade');
            $table->integer('sequence')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('competencies')->nullable();
            $table->json('learning_links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_materials');
        Schema::dropIfExists('internship_curriculums');
    }
};
