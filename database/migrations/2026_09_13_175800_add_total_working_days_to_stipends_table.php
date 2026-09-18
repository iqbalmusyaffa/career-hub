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
        if (Schema::hasTable('internship_stipend_disbursements') && !Schema::hasColumn('internship_stipend_disbursements', 'total_working_days')) {
            Schema::table('internship_stipend_disbursements', function (Blueprint $table) {
                $table->integer('total_working_days')->default(22)->after('unexcused_days');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('internship_stipend_disbursements') && Schema::hasColumn('internship_stipend_disbursements', 'total_working_days')) {
            Schema::table('internship_stipend_disbursements', function (Blueprint $table) {
                $table->dropColumn('total_working_days');
            });
        }
    }
};
