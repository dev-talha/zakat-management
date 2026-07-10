<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Donors table
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('donor_type', ['individual', 'corporate', 'mosque', 'branch', 'institutional'])->default('individual');
            $table->string('display_name');
            $table->string('legal_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->boolean('anonymous_default')->default(false);
            $table->enum('kyc_status', ['none', 'pending', 'verified', 'rejected'])->default('none');
            $table->text('kyc_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['donor_type', 'kyc_status']);
        });

        // Donor addresses
        Schema::create('donor_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
            $table->string('country', 5)->default('BD');
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('upazila')->nullable();
            $table->text('address_line')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->timestamps();
        });

        // Campaigns table
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('fund_type', ['zakat', 'sadaqah', 'fitrah', 'waqf', 'emergency', 'general'])->default('zakat');
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->enum('status', ['draft', 'active', 'paused', 'completed', 'cancelled'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['fund_type', 'status']);
        });

        // Payment gateways
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name');
            $table->enum('mode', ['sandbox', 'live'])->default('sandbox');
            $table->json('config_json')->nullable();
            $table->boolean('active')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Collections (donations received)
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no', 30)->unique();
            $table->foreignId('donor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('fund_type', ['zakat', 'sadaqah', 'fitrah', 'waqf', 'emergency', 'admin', 'restricted', 'general'])->default('zakat');
            $table->enum('source_channel', ['online', 'cash', 'bank_transfer', 'cheque', 'mfs', 'card', 'pos', 'payment_link', 'qr'])->default('online');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 5)->default('BDT');
            $table->string('donor_preference')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->enum('status', ['pending', 'validated', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['fund_type', 'status', 'created_at']);
            $table->index(['donor_id', 'created_at']);
        });

        // Payments (gateway transactions)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gateway_id')->nullable()->constrained('payment_gateways')->nullOnDelete();
            $table->string('provider_ref')->nullable();
            $table->string('tran_id')->nullable();
            $table->string('session_key')->nullable();
            $table->string('redirect_url')->nullable();
            $table->enum('callback_status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->enum('validated_status', ['pending', 'valid', 'invalid', 'error'])->default('pending');
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->json('gateway_response')->nullable();
            $table->timestamps();
            $table->index(['tran_id', 'provider_ref']);
            $table->index(['gateway_id', 'validated_status']);
        });

        // Zakat calculations
        Schema::create('zakat_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('rule_pack', 50)->default('standard');
            $table->string('nisab_basis', 20)->default('silver');
            $table->decimal('nisab_value', 15, 2)->default(0);
            $table->json('asset_snapshot_json');
            $table->json('liability_snapshot_json')->nullable();
            $table->decimal('total_assets', 15, 2)->default(0);
            $table->decimal('total_liabilities', 15, 2)->default(0);
            $table->decimal('net_zakatable', 15, 2)->default(0);
            $table->decimal('zakat_due', 15, 2)->default(0);
            $table->decimal('zakat_rate', 5, 4)->default(0.025);
            $table->boolean('is_eligible')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakat_calculations');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('donor_addresses');
        Schema::dropIfExists('donors');
    }
};
