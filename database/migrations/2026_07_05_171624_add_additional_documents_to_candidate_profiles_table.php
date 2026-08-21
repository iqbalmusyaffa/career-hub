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
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->string('cover_letter_path')->nullable();
            $table->string('skck_path')->nullable();
            $table->string('health_certificate_path')->nullable();
            $table->string('consent_letter_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'cover_letter_path',
                'skck_path',
                'health_certificate_path',
                'consent_letter_path'
            ]);
        });
    }
};
