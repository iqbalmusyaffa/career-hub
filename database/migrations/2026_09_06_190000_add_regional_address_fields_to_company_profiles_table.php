<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('company_profiles', 'province')) {
                $table->string('province')->nullable()->after('address');
            }
            if (!Schema::hasColumn('company_profiles', 'city')) {
                $table->string('city')->nullable()->after('province');
            }
            if (!Schema::hasColumn('company_profiles', 'district')) {
                $table->string('district')->nullable()->after('city');
            }
            if (!Schema::hasColumn('company_profiles', 'village')) {
                $table->string('village')->nullable()->after('district');
            }
            if (!Schema::hasColumn('company_profiles', 'postal_code')) {
                $table->string('postal_code', 10)->nullable()->after('village');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['province', 'city', 'district', 'village', 'postal_code']);
        });
    }
};
