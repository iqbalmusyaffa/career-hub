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
        Schema::create('umk_references', function (Blueprint $table) {
            $table->id();
            $table->string('province');
            $table->string('city_district'); // e.g. Kota Bekasi, Kabupaten Karawang
            $table->decimal('umk_amount', 12, 2); // e.g. 5999443.00
            $table->integer('year')->default(2026);
            $table->string('legal_decree')->nullable(); // e.g. KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umk_references');
    }
};
