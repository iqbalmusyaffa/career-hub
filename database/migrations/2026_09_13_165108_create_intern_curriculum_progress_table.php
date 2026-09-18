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
        Schema::create('intern_curriculum_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('curriculum_material_id')->constrained('curriculum_materials')->onDelete('cascade');
            $table->string('status', 30)->default('pending'); // pending, in_progress, completed
            $table->foreignId('mentor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();
            $table->text('mentor_notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'curriculum_material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intern_curriculum_progress');
    }
};
