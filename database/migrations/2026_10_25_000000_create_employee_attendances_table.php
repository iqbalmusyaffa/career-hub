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
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->nullOnDelete();
            $table->foreignId('company_branch_id')->nullable()->constrained('company_branches')->nullOnDelete();
            $table->date('date')->index();
            $table->string('work_type', 30)->default('wfo'); // wfo, wfh, dinas_luar
            $table->string('status', 30)->default('present'); // present, late, leave, sick
            
            // Check-in details
            $table->string('check_in_time', 20)->nullable();
            $table->string('check_in_selfie_path')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->text('check_in_address')->nullable();
            $table->string('check_in_status', 30)->default('on_time'); // on_time, late
            $table->text('check_in_notes')->nullable();

            // Check-out details
            $table->string('check_out_time', 20)->nullable();
            $table->string('check_out_selfie_path')->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->text('check_out_address')->nullable();
            $table->text('check_out_notes')->nullable();
            $table->unsignedInteger('work_duration_minutes')->nullable();

            // Audit
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'date'], 'employee_attendance_user_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
    }
};
