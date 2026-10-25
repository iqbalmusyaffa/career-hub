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
        // 1. Employee Overtime Requests
        Schema::create('employee_overtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->nullOnDelete();
            $table->date('date')->index();
            $table->string('start_time', 10);
            $table->string('end_time', 10);
            $table->decimal('duration_hours', 4, 2)->default(0);
            $table->text('task_description');
            $table->string('compensation_type', 30)->default('paid'); // paid, leave_balance
            $table->unsignedBigInteger('hourly_rate')->default(35000);
            $table->unsignedBigInteger('total_compensation')->default(0);
            $table->string('attachment_path')->nullable();
            $table->string('status', 20)->default('pending'); // pending, approved, rejected, cancelled
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->boolean('is_payroll_calculated')->default(false);
            $table->timestamps();
        });

        // 2. Employee Expense Claims & Reimbursements
        Schema::create('employee_reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->nullOnDelete();
            $table->string('claim_number', 50)->unique();
            $table->string('category', 50)->default('operational'); // transport, medical, communication, meal_overtime, operational, other
            $table->string('title', 255);
            $table->date('transaction_date')->index();
            $table->unsignedBigInteger('amount');
            $table->text('description');
            $table->string('receipt_path');
            $table->string('bank_name', 50)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_account_holder', 150)->nullable();
            $table->string('status', 20)->default('pending'); // pending, approved, rejected, paid
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('disbursement_proof_path')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->boolean('is_included_in_payroll')->default(false);
            $table->timestamps();
        });

        // 3. Employee Payroll & Payslips
        Schema::create('employee_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->nullOnDelete();
            $table->string('payroll_number', 50)->unique();
            $table->string('period_month', 7)->index(); // YYYY-MM
            $table->string('period_label', 50);
            $table->string('job_title', 150);
            $table->string('employment_type', 30)->default('permanent'); // permanent, contract, probation
            $table->date('payment_date')->nullable();
            
            // Earnings
            $table->unsignedBigInteger('base_salary')->default(0);
            $table->unsignedBigInteger('allowance_fixed')->default(0);
            $table->unsignedBigInteger('allowance_transport')->default(0);
            $table->unsignedBigInteger('overtime_pay')->default(0);
            $table->unsignedBigInteger('reimbursement_pay')->default(0);
            $table->unsignedBigInteger('bonus_pay')->default(0);
            
            // Deductions
            $table->unsignedBigInteger('deduction_late')->default(0);
            $table->unsignedBigInteger('deduction_unexcused')->default(0);
            $table->unsignedBigInteger('deduction_bpjs_tk')->default(0);
            $table->unsignedBigInteger('deduction_bpjs_kes')->default(0);
            $table->unsignedBigInteger('deduction_pph21')->default(0);
            $table->unsignedBigInteger('deduction_other')->default(0);

            // Totals
            $table->unsignedBigInteger('total_earnings')->default(0);
            $table->unsignedBigInteger('total_deductions')->default(0);
            $table->unsignedBigInteger('net_salary')->default(0);

            // Metrics
            $table->unsignedInteger('present_days')->default(0);
            $table->unsignedInteger('late_days')->default(0);
            $table->unsignedInteger('absent_days')->default(0);
            $table->decimal('overtime_hours', 5, 2)->default(0);

            // Bank details
            $table->string('bank_name', 50)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_account_holder', 150)->nullable();

            // Status & Audit
            $table->string('status', 20)->default('draft'); // draft, published, paid
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'period_month'], 'emp_payroll_user_period_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payrolls');
        Schema::dropIfExists('employee_reimbursements');
        Schema::dropIfExists('employee_overtimes');
    }
};
