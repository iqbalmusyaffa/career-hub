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
        Schema::create('internship_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->onDelete('set null');
            $table->string('name', 150);
            $table->string('code', 50)->nullable()->index();
            $table->string('category', 50)->default('other'); // bpjs_tk, bpjs_kes, transport, meal, equipment, wellness, mentoring, allowance, other
            $table->boolean('is_mandatory')->default(false); // true for BPJS Ketenagakerjaan & BPJS Kesehatan
            $table->string('coverage_type', 50)->default('company_paid'); // company_paid, cash_allowance, facility_access, non_cash
            $table->decimal('standard_nominal', 12, 2)->default(0);
            $table->string('provider', 150)->nullable(); // e.g. BPJS Ketenagakerjaan RI, BPJS Kesehatan RI, Internal PT Perusahaan
            $table->text('description')->nullable();
            $table->text('terms')->nullable();
            $table->string('icon', 100)->default('fa-solid fa-shield-halved');
            $table->string('badge_color', 50)->default('indigo');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('internship_user_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('internship_benefit_id')->constrained('internship_benefits')->onDelete('cascade');
            $table->foreignId('application_id')->nullable()->constrained('applications')->onDelete('cascade');
            $table->string('policy_number', 100)->nullable(); // Nomor Kartu BPJS / Nomor Registrasi / No. Inventaris
            $table->string('status', 30)->default('active'); // active, pending, inactive
            $table->decimal('custom_nominal', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'internship_benefit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_user_benefits');
        Schema::dropIfExists('internship_benefits');
    }
};
