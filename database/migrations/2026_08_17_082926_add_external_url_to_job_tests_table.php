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
        Schema::table('job_tests', function (Blueprint $table) {
            $table->string('test_mode')->default('internal')->after('category');
            $table->text('external_url')->nullable()->after('test_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_tests', function (Blueprint $table) {
            $table->dropColumn(['test_mode', 'external_url']);
        });
    }
};
