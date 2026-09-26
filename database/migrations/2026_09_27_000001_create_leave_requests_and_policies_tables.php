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
        // 1. Company Leave Policies Table
        Schema::create('company_leave_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->nullOnDelete();
            $table->integer('annual_leave_quota')->default(12); // Default 12 or 15 days
            $table->integer('permanent_leave_quota')->default(15); // Default 15 days for PKWTT
            $table->integer('internship_max_excused_days')->default(4); // Max 4 days for interns
            $table->boolean('allow_half_day')->default(false);
            $table->boolean('require_attachment_for_sick')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Leave Requests Table
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->nullOnDelete();
            $table->foreignId('application_id')->nullable()->constrained('applications')->nullOnDelete();
            
            // Type of leave: annual_leave, sick_leave, maternity_leave, special_leave, internship_permission
            $table->string('leave_type')->default('annual_leave');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days')->default(1);
            $table->text('reason');
            $table->string('attachment_path')->nullable();
            
            // Status: pending, approved, rejected, cancelled
            $table->string('status')->default('pending');
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('company_leave_policies');
    }
};
