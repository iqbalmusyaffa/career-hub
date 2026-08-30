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
        Schema::create('company_holiday_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('company_holiday_id')->constrained('company_holidays')->onDelete('cascade');
            $table->boolean('is_working_day')->default(true); // true = HR/Mentor requires interns to work on this holiday
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'company_holiday_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_holiday_overrides');
    }
};
