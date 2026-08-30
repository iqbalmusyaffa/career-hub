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
        Schema::create('internship_unlock_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('intern_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->onDelete('set null');
            $table->date('target_date');
            $table->string('category'); // platform_outage, partner_issue, force_majeure
            $table->text('description');
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamp('unlocked_until')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_unlock_requests');
    }
};
