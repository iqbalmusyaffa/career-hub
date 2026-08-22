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
        Schema::table('candidate_onboardings', function (Blueprint $table) {
            $table->string('student_id_number')->nullable()->after('family_card_doc_path');
            $table->string('institution_name')->nullable()->after('student_id_number');
            $table->string('internship_letter_doc_path')->nullable()->after('institution_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_onboardings', function (Blueprint $table) {
            //
        });
    }
};
