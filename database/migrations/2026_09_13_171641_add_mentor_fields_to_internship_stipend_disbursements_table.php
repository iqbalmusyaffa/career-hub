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
        Schema::table('internship_stipend_disbursements', function (Blueprint $table) {
            if (!Schema::hasColumn('internship_stipend_disbursements', 'mentor_id')) {
                $table->foreignId('mentor_id')->nullable()->after('verified_by')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('internship_stipend_disbursements', 'mentor_notes')) {
                $table->text('mentor_notes')->nullable()->after('mentor_id');
            }
            if (!Schema::hasColumn('internship_stipend_disbursements', 'mentor_submitted_at')) {
                $table->timestamp('mentor_submitted_at')->nullable()->after('mentor_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internship_stipend_disbursements', function (Blueprint $table) {
            if (Schema::hasColumn('internship_stipend_disbursements', 'mentor_id')) {
                $table->dropForeign(['mentor_id']);
                $table->dropColumn('mentor_id');
            }
            if (Schema::hasColumn('internship_stipend_disbursements', 'mentor_notes')) {
                $table->dropColumn('mentor_notes');
            }
            if (Schema::hasColumn('internship_stipend_disbursements', 'mentor_submitted_at')) {
                $table->dropColumn('mentor_submitted_at');
            }
        });
    }
};
