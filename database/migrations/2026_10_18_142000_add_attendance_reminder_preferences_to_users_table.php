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
            $table->boolean('email_attendance_reminder_enabled')->default(true)->after('theme_preference');
            $table->boolean('inapp_attendance_reminder_enabled')->default(true)->after('email_attendance_reminder_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_attendance_reminder_enabled',
                'inapp_attendance_reminder_enabled',
            ]);
        });
    }
};
