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
        Schema::create('internship_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
            $table->string('batch_name');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('target_hours')->default(400);
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, closed, draft
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['company_id', 'batch_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_batches');
    }
};
