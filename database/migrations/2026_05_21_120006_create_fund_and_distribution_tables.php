<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->enum('type', ['zakat', 'sadaqah', 'fitrah', 'waqf', 'emergency', 'admin', 'restricted', 'general'])->default('zakat');
            $table->boolean('restricted_flag')->default(false);
            $table->boolean('branch_scoped_flag')->default(false);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('fund_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('entry_type', ['collection', 'distribution', 'transfer', 'adjustment', 'refund', 'fee']);
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('ref_type')->nullable();
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->text('narration')->nullable();
            $table->timestamp('effective_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['fund_id', 'effective_at']);
        });

        Schema::create('distribution_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no', 30)->unique();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('fund_id')->constrained();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->integer('total_beneficiaries')->default(0);
            $table->enum('approval_status', ['draft', 'pending', 'approved', 'rejected', 'processing', 'completed'])->default('draft');
            $table->string('payout_channel')->nullable();
            $table->foreignId('prepared_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained();
            $table->foreignId('case_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('fund_id')->constrained();
            $table->foreignId('batch_id')->nullable()->constrained('distribution_batches')->nullOnDelete();
            $table->string('category_code')->nullable();
            $table->decimal('approved_amount', 15, 2)->default(0);
            $table->enum('distribution_type', ['cash', 'bank_transfer', 'mfs', 'voucher', 'food_pack', 'medical', 'education', 'livelihood', 'housing', 'other'])->default('cash');
            $table->enum('status', ['pending', 'approved', 'disbursed', 'acknowledged', 'failed', 'reversed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->nullable()->constrained('distribution_batches')->nullOnDelete();
            $table->foreignId('distribution_id')->constrained()->cascadeOnDelete();
            $table->string('payout_ref')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('payout_channel')->nullable();
            $table->enum('provider_status', ['initiated', 'submitted', 'accepted', 'settled', 'failed', 'reversed', 'manually_resolved'])->default('initiated');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disbursements');
        Schema::dropIfExists('distributions');
        Schema::dropIfExists('distribution_batches');
        Schema::dropIfExists('fund_ledgers');
        Schema::dropIfExists('funds');
    }
};
