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
        Schema::create('candidate_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('evaluator_user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('rating')->default(5); // 1 - 5 stars
            $table->unsignedTinyInteger('technical_score')->default(80); // 1 - 100
            $table->unsignedTinyInteger('attitude_score')->default(80); // 1 - 100
            $table->unsignedTinyInteger('communication_score')->default(80); // 1 - 100
            $table->text('comments')->nullable();
            $table->string('recommendation')->default('hire'); // hire, consider, reject
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_evaluations');
    }
};
