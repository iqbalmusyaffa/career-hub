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
            $table->string('mentor_phone')->nullable()->after('mentor_name');
            $table->string('mentor_email')->nullable()->after('mentor_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internship_certificates', function (Blueprint $table) {
            //
        });
    }
};
