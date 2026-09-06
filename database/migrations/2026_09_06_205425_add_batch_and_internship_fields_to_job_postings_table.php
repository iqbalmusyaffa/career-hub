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
            if (!Schema::hasColumn('job_postings', 'batch')) {
                $table->string('batch')->nullable()->after('quota');
            }
            if (!Schema::hasColumn('job_postings', 'duration')) {
                $table->string('duration')->nullable()->after('batch');
            }
            if (!Schema::hasColumn('job_postings', 'start_date')) {
                $table->date('start_date')->nullable()->after('duration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn(['batch', 'duration', 'start_date']);
        });
    }
};
