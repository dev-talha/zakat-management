<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop orphaned tables from any previous failed run
        Schema::dropIfExists('seasonal_modes');
        Schema::dropIfExists('sms_status_queries');
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('porichoy_verifications');
        Schema::dropIfExists('mosque_collections');
        Schema::dropIfExists('geographic_areas');

        // বাংলাদেশের পূর্ণ ভৌগোলিক শ্রেণীবিন্যাস: বিভাগ → জেলা → উপজেলা → ইউনিয়ন → ওয়ার্ড → গ্রাম
        Schema::create('geographic_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable(); // Self-referencing hierarchy
            $table->enum('level', ['division', 'district', 'upazila', 'union', 'ward', 'village'])
                  ->default('village');
            $table->string('name_bn');
            $table->string('name_en');
            $table->string('bbs_code', 20)->nullable();          // BBS (Bangladesh Bureau of Statistics) code
            $table->enum('area_type', ['rural', 'semi_urban', 'urban'])->default('rural');
            $table->decimal('poverty_index', 5, 2)->default(0);  // 0-100, higher = poorer
            $table->unsignedInteger('population_estimate')->default(0);
            $table->decimal('geo_lat', 10, 7)->nullable();       // Center point
            $table->decimal('geo_lng', 10, 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('geographic_areas')->nullOnDelete();
            $table->index(['parent_id', 'level']);
            $table->index(['level', 'area_type']);
            $table->index(['bbs_code']);
        });

        // মসজিদ কালেকশন — মসজিদ থেকে নগদ সংগ্রহের রেকর্ড
        Schema::create('mosque_collections', function (Blueprint $table) {
            $table->id();
            $table->string('collection_no', 30)->unique();
            $table->foreignId('mosque_id')->constrained('mosques')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('collected_by_name');                 // Imam / committee member name
            $table->string('collected_by_mobile', 20)->nullable();
            $table->string('collection_type')->default('zakat'); // zakat, fitrah, sadaqah
            $table->decimal('cash_amount', 15, 2)->default(0);
            $table->date('collection_date');
            $table->string('prayer_time')->nullable();           // e.g. 'juma', 'asr', 'isha'
            $table->text('notes')->nullable();
            $table->string('receipt_image')->nullable();         // Photo of physical receipt
            $table->enum('deposit_status', ['pending', 'deposited', 'reconciled'])->default('pending');
            $table->foreignId('deposited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('deposited_at')->nullable();
            $table->foreignId('fund_ledger_id')->nullable();     // Link to ledger once posted
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['mosque_id', 'deposit_status']);
            $table->index(['collection_date']);
        });

        // Porichoy API যাচাই লগ
        Schema::create('porichoy_verifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('verifiable');                        // beneficiary or donor
            $table->string('nid_no', 30);
            $table->string('dob')->nullable();                   // Used in verification
            $table->enum('status', ['pending', 'matched', 'not_found', 'mismatch', 'error', 'skipped'])
                  ->default('pending');
            $table->json('api_response')->nullable();            // Sanitized response (no PII logged raw)
            $table->string('matched_name')->nullable();
            $table->string('matched_dob')->nullable();
            $table->boolean('photo_matched')->nullable();
            $table->string('api_request_id')->nullable();        // Porichoy's request ID
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['nid_no', 'status']);
        });

        // SMS লগ — sms.net.bd দিয়ে পাঠানো সব SMS এর রেকর্ড
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('recipient');                         // user, beneficiary, donor
            $table->string('to_number', 20);
            $table->text('message');
            $table->string('template_code', 60)->nullable();     // e.g. 'status_update', 'otp', 'disbursement'
            $table->string('sender_id', 20)->nullable();
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed'])->default('pending');
            $table->string('gateway_ref')->nullable();           // sms.net.bd response ID
            $table->json('gateway_response')->nullable();
            $table->integer('cost_unit')->default(1);            // SMS units consumed
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['to_number', 'status']);
            $table->index(['template_code', 'created_at']);
        });

        // SMS স্ট্যাটাস চেক ইনকামিং — "ZAKAT STATUS BEN-2026-001234" parse করা
        Schema::create('sms_status_queries', function (Blueprint $table) {
            $table->id();
            $table->string('from_number', 20);
            $table->text('raw_message');
            $table->string('query_type', 30)->nullable();        // 'status', 'help', 'unknown'
            $table->string('reference_no', 40)->nullable();      // e.g. BEN-2026-001234
            $table->boolean('was_resolved')->default(false);
            $table->foreignId('sms_log_id')->nullable();         // Reply SMS link
            $table->timestamp('received_at');
            $table->timestamps();

            $table->index(['from_number']);
            $table->index(['received_at']);
        });

        // মৌসুমী মোড কনফিগারেশন
        Schema::create('seasonal_modes', function (Blueprint $table) {
            $table->id();
            $table->string('mode_code', 40)->unique();           // 'ramadan', 'eid_adha', 'flood', 'emergency'
            $table->string('name_bn');
            $table->string('name_en');
            $table->text('description_bn')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('auto_activate')->default(false);    // Activate automatically by date
            $table->json('overrides')->nullable();               // Settings overrides during this mode
            $table->foreignId('activated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasonal_modes');
        Schema::dropIfExists('sms_status_queries');
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('porichoy_verifications');
        Schema::dropIfExists('mosque_collections');
        Schema::dropIfExists('geographic_areas');
    }
};
