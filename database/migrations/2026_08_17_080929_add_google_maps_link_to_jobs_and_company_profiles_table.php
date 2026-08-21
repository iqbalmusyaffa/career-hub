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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->text('google_maps_link')->nullable()->after('location');
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->text('google_maps_link')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('google_maps_link');
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn('google_maps_link');
        });
    }
};
