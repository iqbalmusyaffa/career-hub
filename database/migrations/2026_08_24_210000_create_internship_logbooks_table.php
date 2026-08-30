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
        Schema::create('internship_logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('application_id')->nullable()->constrained('applications')->onDelete('cascade');
            $table->foreignId('mentor_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->date('date');
            $table->string('attendance_type')->default('present'); // present, sick, permission, absent, wfh, wfo
            $table->integer('work_hours')->default(8);
            
            // Geolocation GPS
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('location_address')->nullable();
            
            // Logbook details (Gambar 2)
            $table->text('activities')->nullable(); // Uraian Aktivitas
            $table->text('learnings')->nullable();  // Pembelajaran yang Diperoleh
            $table->text('challenges')->nullable(); // Kendala yang Dialami
            
            // Approval workflow
            $table->string('status')->default('pending'); // pending, approved, rejected, action_required
            $table->text('mentor_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_logbooks');
    }
};
