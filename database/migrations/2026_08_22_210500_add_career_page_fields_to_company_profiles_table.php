<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("company_profiles", function (Blueprint $table) {
            $table->string("cover_image_path")->nullable()->after("logo_path");
            $table->string("tagline")->nullable()->after("company_name");
            $table->text("culture_description")->nullable()->after("description");
            $table->json("benefits")->nullable()->after("culture_description");
        });
    }

    public function down(): void
    {
        Schema::table("company_profiles", function (Blueprint $table) {
            $table->dropColumn(["cover_image_path", "tagline", "culture_description", "benefits"]);
        });
    }
};

