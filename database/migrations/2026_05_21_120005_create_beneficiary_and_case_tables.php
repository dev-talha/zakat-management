<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Beneficiaries
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('application_no', 30)->unique();
            $table->string('primary_person_name');
            $table->string('primary_person_name_bn')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            $table->enum('identity_type', ['nid', 'birth_cert', 'passport', 'other', 'none'])->default('none');
            $table->string('identity_no')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->decimal('monthly_income', 10, 2)->default(0);
            $table->decimal('total_assets_value', 15, 2)->default(0);
            $table->decimal('total_liabilities', 15, 2)->default(0);
            $table->text('medical_status')->nullable();
            $table->boolean('disability_flag')->default(false);
            $table->string('disability_type')->nullable();
            $table->string('education_level')->nullable();
            $table->string('employment_status')->nullable();
            $table->integer('vulnerability_score')->default(0);
            $table->string('zakat_category')->nullable();
            $table->enum('status', ['pending', 'under_review', 'verified', 'approved', 'rejected', 'blacklisted', 'graduated'])->default('pending');
            $table->boolean('blacklist_flag')->default(false);
            $table->boolean('watchlist_flag')->default(false);
            $table->integer('duplicate_confidence_score')->default(0);
            $table->text('rejection_reason')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'branch_id']);
            $table->index(['identity_type', 'identity_no']);
            $table->index(['mobile']);
            $table->index(['zakat_category']);
        });

        // Households
        Schema::create('beneficiary_households', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->text('address')->nullable();
            $table->text('address_bn')->nullable();
            $table->decimal('geo_lat', 10, 7)->nullable();
            $table->decimal('geo_lng', 10, 7)->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('upazila')->nullable();
            $table->string('ward')->nullable();
            $table->string('union_name')->nullable();
            $table->string('village')->nullable();
            $table->enum('housing_type', ['own', 'rented', 'government', 'shelter', 'homeless', 'other'])->nullable();
            $table->string('housing_condition')->nullable();
            $table->timestamps();
        });

        // Household members
        Schema::create('household_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained('beneficiary_households')->cascadeOnDelete();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('relation');
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->boolean('disability_flag')->default(false);
            $table->string('education_status')->nullable();
            $table->string('employment_status')->nullable();
            $table->decimal('income', 10, 2)->default(0);
            $table->timestamps();
        });

        // Beneficiary documents
        Schema::create('beneficiary_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->string('doc_type');
            $table->string('original_name')->nullable();
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->default(0);
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamps();
        });

        // Field agents
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('area_code')->nullable();
            $table->string('coverage_district')->nullable();
            $table->string('coverage_upazila')->nullable();
            $table->enum('onboarding_status', ['pending', 'trained', 'active', 'suspended'])->default('pending');
            $table->integer('capacity_score')->default(100);
            $table->integer('active_cases_count')->default(0);
            $table->timestamps();
        });

        // Cases
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_no', 30)->unique();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('case_type', ['food', 'medical', 'education', 'debt_relief', 'livelihood', 'housing', 'emergency', 'rehabilitation', 'general'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('stage', ['assessment', 'field_verification', 'supervisor_review', 'shariah_review', 'finance_review', 'approved', 'disbursement', 'follow_up', 'closed', 'rejected'])->default('assessment');
            $table->string('source')->nullable();
            $table->decimal('requested_amount', 15, 2)->default(0);
            $table->decimal('approved_amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->enum('outcome_status', ['pending', 'successful', 'partial', 'failed', 'graduated'])->default('pending');
            $table->date('follow_up_date')->nullable();
            $table->foreignId('assigned_agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['stage', 'priority']);
            $table->index(['beneficiary_id', 'stage']);
        });

        // Case notes
        Schema::create('case_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->enum('note_type', ['general', 'assessment', 'visit', 'decision', 'follow_up', 'escalation'])->default('general');
            $table->text('body');
            $table->timestamps();
        });

        // Verification visits
        Schema::create('verification_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $table->timestamp('visit_at')->nullable();
            $table->decimal('gps_lat', 10, 7)->nullable();
            $table->decimal('gps_lng', 10, 7)->nullable();
            $table->text('summary')->nullable();
            $table->text('summary_bn')->nullable();
            $table->json('interview_data_json')->nullable();
            $table->boolean('risk_flag')->default(false);
            $table->string('risk_reason')->nullable();
            $table->json('photo_paths')->nullable();
            $table->json('document_paths')->nullable();
            $table->enum('supervisor_status', ['pending', 'approved', 'returned', 'rejected'])->default('pending');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('supervisor_notes')->nullable();
            $table->timestamp('supervisor_reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['case_id', 'supervisor_status']);
        });

        // Approvals (polymorphic)
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('step_name');
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('decision', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
            $table->index(['entity_type', 'entity_id']);
            $table->index(['approver_id', 'decision']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('verification_visits');
        Schema::dropIfExists('case_notes');
        Schema::dropIfExists('cases');
        Schema::dropIfExists('agents');
        Schema::dropIfExists('beneficiary_documents');
        Schema::dropIfExists('household_members');
        Schema::dropIfExists('beneficiary_households');
        Schema::dropIfExists('beneficiaries');
    }
};
