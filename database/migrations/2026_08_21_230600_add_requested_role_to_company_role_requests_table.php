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
        Schema::table('company_role_requests', function (Blueprint $table) {
            $table->string('requested_role')->default('Company Owner')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_role_requests', function (Blueprint $table) {
            $table->dropColumn('requested_role');
        });
    }
};
