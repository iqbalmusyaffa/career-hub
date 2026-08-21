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
        Schema::create('company_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->string('branch_name'); // e.g. Kantor Pusat Jakarta, Cabang Surabaya
            $table->string('city'); // e.g. Jakarta Selatan, Kota Surabaya
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_headquarter')->default(false);
            $table->timestamps();
        });

        // Add branch reference to job_postings
        if (Schema::hasTable('job_postings') && !Schema::hasColumn('job_postings', 'company_branch_id')) {
            Schema::table('job_postings', function (Blueprint $table) {
                $table->foreignId('company_branch_id')->nullable()->constrained('company_branches')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('job_postings') && Schema::hasColumn('job_postings', 'company_branch_id')) {
            Schema::table('job_postings', function (Blueprint $table) {
                $table->dropForeign(['company_branch_id']);
                $table->dropColumn('company_branch_id');
            });
        }

        Schema::dropIfExists('company_branches');
    }
};
