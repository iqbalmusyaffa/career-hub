<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained('job_postings')->onDelete('cascade');
            $table->string('company_name');
            $table->string('report_category'); // e.g. deposit_fee, diploma_withholding, under_umk, fake_company, harassment
            $table->text('reason_description');
            $table->string('evidence_url')->nullable();
            $table->enum('status', ['pending', 'investigating', 'resolved_blacklisted', 'dismissed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_reports');
    }
};
