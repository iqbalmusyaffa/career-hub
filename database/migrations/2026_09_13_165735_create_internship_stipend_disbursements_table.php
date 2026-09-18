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
        Schema::create('internship_stipend_disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('company_profiles')->onDelete('set null');
            $table->string('period_month', 7); // e.g. '2026-08'
            $table->string('period_label', 100); // e.g. 'Agustus 2026'
            $table->string('batch_name', 150)->nullable();
            
            // Snapshot of verified Bank Account
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->string('bank_book_doc_path')->nullable();
            $table->boolean('is_ktp_matched')->default(true);

            // Attendance & Calculation breakdown
            $table->integer('present_days')->default(0);
            $table->integer('excused_days')->default(0);
            $table->integer('unexcused_days')->default(0);
            $table->decimal('base_nominal', 12, 2)->default(2800000);
            $table->decimal('deduction_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(2800000);

            // Status & Verification
            $table->string('status', 30)->default('in_review'); // in_review, ready, transferred
            $table->string('proof_path')->nullable();
            $table->timestamp('transferred_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'period_month']);
        });

        if (Schema::hasTable('candidate_onboardings') && !Schema::hasColumn('candidate_onboardings', 'bank_book_doc_path')) {
            Schema::table('candidate_onboardings', function (Blueprint $table) {
                $table->string('bank_book_doc_path')->nullable()->after('bank_account_holder');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_stipend_disbursements');
        if (Schema::hasTable('candidate_onboardings') && Schema::hasColumn('candidate_onboardings', 'bank_book_doc_path')) {
            Schema::table('candidate_onboardings', function (Blueprint $table) {
                $table->dropColumn('bank_book_doc_path');
            });
        }
    }
};
