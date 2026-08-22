<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('headcount_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_user_id')->constrained('users')->onDelete('cascade');
            $table->string('division'); // e.g. IT Engineering, Marketing, Finance, HR, Sales, Operations
            $table->unsignedSmallInteger('fiscal_year')->default(2026);
            $table->unsignedInteger('target_headcount')->default(1);
            $table->decimal('allocated_budget', 15, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('headcount_budgets');
    }
};
