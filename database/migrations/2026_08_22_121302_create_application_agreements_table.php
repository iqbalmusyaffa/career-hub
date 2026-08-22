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
        Schema::create('application_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('agreement_type'); // employment_contract, internship_agreement
            $table->string('title');
            $table->string('contract_number');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('stipend_or_salary');
            $table->longText('terms_content');
            $table->longText('signature_data')->nullable();
            $table->string('signer_name')->nullable();
            $table->string('signer_ip')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('status')->default('sent'); // sent, signed, declined
            $table->string('signed_pdf_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_agreements');
    }
};
