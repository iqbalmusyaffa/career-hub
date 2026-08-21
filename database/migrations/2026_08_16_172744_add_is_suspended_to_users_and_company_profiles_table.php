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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_suspended')) {
                $table->boolean('is_suspended')->default(false)->after('password');
            }
            if (!Schema::hasColumn('users', 'status_reason')) {
                $table->string('status_reason')->nullable()->after('is_suspended');
            }
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('company_profiles', 'is_suspended')) {
                $table->boolean('is_suspended')->default(false)->after('is_verified');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_suspended', 'status_reason']);
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['is_suspended']);
        });
    }
};
