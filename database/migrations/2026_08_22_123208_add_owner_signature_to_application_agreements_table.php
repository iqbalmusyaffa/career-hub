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
            $table->string('owner_signer_name')->nullable()->after('hr_signer_name');
            $table->string('owner_signature_path')->nullable()->after('company_signature_path');
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
