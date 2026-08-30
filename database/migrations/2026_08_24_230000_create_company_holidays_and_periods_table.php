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
        Schema::create('company_holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
            $table->date('date');
            $table->string('name');
            $table->string('type')->default('national_holiday'); // national_holiday, cuti_bersama, company_holiday
            $table->timestamps();

            $table->unique(['company_id', 'date']);
        });

        Schema::create('internship_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('period_name')->default('Periode 1');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('target_hours')->default(400);
            $table->timestamps();

            $table->unique(['user_id', 'period_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_periods');
        Schema::dropIfExists('company_holidays');
    }
};
