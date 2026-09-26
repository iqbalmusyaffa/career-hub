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
        Schema::create('internship_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->nullOnDelete();
            $table->unsignedTinyInteger('mentor_rating')->default(5);
            $table->unsignedTinyInteger('program_rating')->default(5);
            $table->unsignedTinyInteger('environment_rating')->default(5);
            $table->unsignedTinyInteger('career_readiness_rating')->default(5);
            $table->string('recommendation_nps')->default('highly_recommended');
            $table->boolean('is_anonymous')->default(false);
            $table->text('feedback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_surveys');
    }
};
