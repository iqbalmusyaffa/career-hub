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
        Schema::table('internship_certificates', function (Blueprint $table) {
            $table->boolean('is_revoked')->default(false)->after('pdf_path');
            $table->timestamp('revoked_at')->nullable()->after('is_revoked');
            $table->text('revocation_reason')->nullable()->after('revoked_at');
            $table->foreignId('revoked_by')->nullable()->constrained('users')->onDelete('set null')->after('revocation_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internship_certificates', function (Blueprint $table) {
            $table->dropForeign(['revoked_by']);
            $table->dropColumn(['is_revoked', 'revoked_at', 'revocation_reason', 'revoked_by']);
        });
    }
};
