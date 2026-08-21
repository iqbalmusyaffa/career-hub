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
        Schema::create('offer_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained('job_postings')->onDelete('cascade');
            $table->string('position_title');
            $table->string('offered_salary');
            $table->date('start_date');
            $table->date('expiration_date')->nullable();
            $table->string('work_location')->nullable();
            $table->text('benefits_summary')->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('pdf_path')->nullable();
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->text('candidate_response_note')->nullable();
            $table->timestamps();
        });

        Schema::create('application_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_messages');
        Schema::dropIfExists('offer_letters');
    }
};
