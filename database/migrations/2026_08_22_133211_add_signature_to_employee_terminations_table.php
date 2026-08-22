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
        Schema::table('employee_terminations', function (Blueprint $table) {
            $table->string('status')->default('pending_signature')->after('document_type'); // pending_signature, signed
            $table->text('signature_data')->nullable()->after('reason_or_recommendation_notes');
            $table->string('signer_name')->nullable()->after('signature_data');
            $table->string('signer_ip')->nullable()->after('signer_name');
            $table->timestamp('signed_at')->nullable()->after('signer_ip');
            $table->string('company_signature_path')->nullable()->after('hr_name');
            $table->string('owner_signature_path')->nullable()->after('owner_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_terminations', function (Blueprint $table) {
            //
        });
    }
};
