<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_scorecards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('interviewer_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('technical_score')->default(3); // 1-5
            $table->unsignedTinyInteger('communication_score')->default(3); // 1-5
            $table->unsignedTinyInteger('problem_solving_score')->default(3); // 1-5
            $table->unsignedTinyInteger('culture_score')->default(3); // 1-5
            $table->decimal('average_score', 3, 1)->default(3.0);
            $table->enum('recommendation', ['strong_hire', 'hire', 'hold', 'no_hire'])->default('hire');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_scorecards');
    }
};
