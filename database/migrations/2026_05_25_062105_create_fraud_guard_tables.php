<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop orphaned tables from any previous failed run
        Schema::dropIfExists('blacklists');
        Schema::dropIfExists('fraud_cases');
        Schema::dropIfExists('duplicate_candidates');
        Schema::dropIfExists('fraud_alerts');
        Schema::dropIfExists('fraud_risk_scores');

        // ফ্রড রিস্ক স্কোর — প্রতিটি আবেদন/উপকারভোগীর রিস্ক মূল্যায়ন
        Schema::create('fraud_risk_scores', function (Blueprint $table) {
            $table->id();
            $table->morphs('subject');                   // subject_type: 'beneficiary' | 'application'
            $table->unsignedTinyInteger('score');        // 0-100
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->json('factors');                     // [{"factor":"duplicate_nid","weight":40,"detail":"..."}]
            $table->text('explanation')->nullable();     // Human-readable Bangla explanation
            $table->enum('flagged_by', ['system', 'agent', 'admin', 'supervisor'])->default('system');
            $table->enum('review_status', ['pending', 'cleared', 'confirmed_fraud'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestamps();

            $table->index(['risk_level', 'review_status']);
            $table->index(['score']);
        });

        // ফ্রড অ্যালার্ট — রিয়েল-টাইম সতর্কতা
        Schema::create('fraud_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('alert_code', 60);            // e.g. 'DUPLICATE_NID', 'GEO_CLUSTER', 'HIGH_RISK'
            $table->enum('severity', ['info', 'warning', 'critical'])->default('warning');
            $table->morphs('subject');
            $table->text('description_bn')->nullable();
            $table->text('description_en')->nullable();
            $table->json('evidence')->nullable();        // Supporting data for the alert
            $table->boolean('is_resolved')->default(false);
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_note')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['alert_code', 'severity']);
            $table->index(['is_resolved', 'severity']);
        });

        // ডুপ্লিকেট ক্যান্ডিডেট — সম্ভাব্য একই ব্যক্তির দুটি রেকর্ড
        Schema::create('duplicate_candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('primary_beneficiary_id');
            $table->unsignedBigInteger('duplicate_beneficiary_id');
            $table->json('match_types');                // ["nid","mobile","name_fuzzy","geo"]
            $table->unsignedTinyInteger('confidence_score'); // 0-100
            $table->json('match_details')->nullable();  // Detail of each match type
            $table->enum('review_status', ['pending', 'confirmed_duplicate', 'false_positive'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();

            $table->foreign('primary_beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('duplicate_beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->unique(['primary_beneficiary_id', 'duplicate_beneficiary_id'], 'dup_candidates_primary_dup_unique');
            $table->index(['review_status', 'confidence_score']);
        });

        // ফ্রড কেস — তদন্তের রেকর্ড
        Schema::create('fraud_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_no', 30)->unique();
            $table->morphs('subject');
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('fraud_type', [
                'duplicate_identity', 'false_information', 'agent_collusion',
                'document_forgery', 'geo_fraud', 'identity_theft', 'other'
            ])->default('other');
            $table->text('description');
            $table->json('evidence')->nullable();       // File paths, data snapshots
            $table->enum('status', ['open', 'investigating', 'confirmed', 'dismissed', 'referred'])->default('open');
            $table->foreignId('investigator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resolution')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'fraud_type']);
        });

        // কালো তালিকা — জাকাত গ্রহণে অযোগ্য ঘোষিত ব্যক্তি
        Schema::create('blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('identity_type', 20)->nullable(); // nid, mobile, name
            $table->string('identity_no', 100)->nullable();
            $table->string('name')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('district')->nullable();
            $table->text('reason');
            $table->enum('severity', ['temporary', 'permanent'])->default('permanent');
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('fraud_case_id')->nullable()->constrained('fraud_cases')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();  // null = permanent
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['identity_type', 'identity_no']);
            $table->index(['mobile']);
            $table->index(['is_active', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklists');
        Schema::dropIfExists('fraud_cases');
        Schema::dropIfExists('duplicate_candidates');
        Schema::dropIfExists('fraud_alerts');
        Schema::dropIfExists('fraud_risk_scores');
    }
};
