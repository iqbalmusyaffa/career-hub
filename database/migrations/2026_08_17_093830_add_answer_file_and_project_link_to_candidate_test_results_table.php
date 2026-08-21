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
        Schema::table('candidate_test_results', function (Blueprint $table) {
            $table->string('answer_file_path')->nullable()->after('answers');
            $table->string('project_url')->nullable()->after('answer_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_test_results', function (Blueprint $table) {
            $table->dropColumn(['answer_file_path', 'project_url']);
        });
    }
};
