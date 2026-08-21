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
            $table->dropColumn(['last_education', 'experience_summary', 'skills']);
            
            $table->string('birth_place')->nullable()->after('phone');
            $table->string('nationality')->nullable()->after('gender');
            $table->string('city')->nullable()->after('address');
            $table->string('province')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('province');
            
            $table->text('summary')->nullable();
            
            $table->json('educations')->nullable();
            $table->json('experiences')->nullable();
            $table->json('organizations')->nullable();
            $table->json('skills')->nullable(); 
            $table->json('languages')->nullable();
            $table->json('certificates')->nullable();
            $table->json('portfolios')->nullable();
            $table->json('achievements')->nullable();
            $table->json('references')->nullable();
            $table->json('job_preferences')->nullable();
            $table->json('social_links')->nullable();
            
            $table->string('ktp_path')->nullable();
            $table->string('ijazah_path')->nullable();
            $table->string('transcript_path')->nullable();
            $table->string('portfolio_file_path')->nullable();
            $table->string('certificate_file_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'birth_place', 'nationality', 'city', 'province', 'postal_code',
                'summary', 'educations', 'experiences', 'organizations',
                'skills', 'languages', 'certificates', 'portfolios',
                'achievements', 'references', 'job_preferences', 'social_links',
                'ktp_path', 'ijazah_path', 'transcript_path', 'portfolio_file_path', 'certificate_file_path'
            ]);
            
            $table->string('last_education')->nullable();
            $table->text('experience_summary')->nullable();
            $table->text('skills')->nullable();
        });
    }
};
