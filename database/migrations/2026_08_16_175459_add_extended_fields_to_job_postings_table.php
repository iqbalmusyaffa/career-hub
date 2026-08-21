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
            if (!Schema::hasColumn('job_postings', 'experience_level')) {
                $table->string('experience_level')->nullable()->after('work_type');
            }
            if (!Schema::hasColumn('job_postings', 'education_level')) {
                $table->string('education_level')->nullable()->after('experience_level');
            }
            if (!Schema::hasColumn('job_postings', 'skills_required')) {
                $table->string('skills_required')->nullable()->after('requirements');
            }
            if (!Schema::hasColumn('job_postings', 'gender_requirement')) {
                $table->string('gender_requirement')->nullable()->after('skills_required');
            }
            if (!Schema::hasColumn('job_postings', 'age_range')) {
                $table->string('age_range')->nullable()->after('gender_requirement');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn([
                'experience_level',
                'education_level',
                'skills_required',
                'gender_requirement',
                'age_range',
            ]);
        });
    }
};
