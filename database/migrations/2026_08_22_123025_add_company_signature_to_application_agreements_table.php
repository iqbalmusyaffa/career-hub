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
        Schema::table('application_agreements', function (Blueprint $table) {
            $table->string('company_signature_path')->nullable()->after('signature_data');
            $table->string('hr_signer_name')->nullable()->after('company_signature_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_agreements', function (Blueprint $table) {
            //
        });
    }
};
