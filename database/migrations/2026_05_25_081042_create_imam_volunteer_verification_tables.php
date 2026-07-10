<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── ইমাম ও মুয়াজ্জিন নিবন্ধন ──────────────────────────────────────
        Schema::create('imam_muezzins', function (Blueprint $table) {
            $table->id();
            $table->string('imam_code', 25)->unique();          // IMAM-2026-00001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mosque_id')->constrained('mosques')->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('role', ['imam', 'muezzin', 'khatib', 'general_validator'])->default('imam');
            $table->string('nid_no', 30)->unique();
            $table->string('name_bn');
            $table->string('name_en')->nullable();
            $table->string('mobile', 20);
            $table->text('qualification_bn')->nullable();       // শিক্ষাগত যোগ্যতা
            $table->unsignedInteger('years_of_service')->default(0);
            $table->text('address_bn')->nullable();

            // ভৌগোলিক কভারেজ — শুধুমাত্র এই এলাকায় যাচাই ও ফলো-আপ করতে পারবেন
            $table->foreignId('coverage_area_id')
                  ->constrained('geographic_areas')->cascadeOnDelete();
            $table->enum('coverage_level', ['village', 'ward', 'union'])->default('ward');
            $table->string('coverage_village')->nullable();     // নির্দিষ্ট গ্রামের নাম

            // অবস্থা
            $table->enum('status', ['pending', 'active', 'suspended', 'removed'])->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // পরিসংখ্যান
            $table->unsignedInteger('total_verifications')->default(0);
            $table->unsignedInteger('total_followups')->default(0);
            $table->unsignedInteger('total_misuse_reports')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['mosque_id', 'status']);
            $table->index(['coverage_area_id']);
        });

        // ── জাকাত আবেদন যাচাই লগ — এলাকা-সীমাবদ্ধ ────────────────────────
        Schema::create('zakat_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();

            // কে যাচাই করলেন
            $table->foreignId('verifier_id')->constrained('users')->cascadeOnDelete();
            $table->enum('verifier_type', ['volunteer', 'imam', 'muezzin', 'general_validator',
                                           'org_admin', 'branch_admin', 'system_admin'])->default('volunteer');
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('volunteer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('imam_muezzin_id')->nullable()->constrained('imam_muezzins')->nullOnDelete();

            // এলাকা যাচাই (সিস্টেম নিশ্চিত করে যে verifier এর এলাকা মিলছে)
            $table->foreignId('verified_area_id')->constrained('geographic_areas')->cascadeOnDelete();
            $table->boolean('is_within_authority')->default(false); // সিস্টেম চেক করে
            $table->boolean('authority_override')->default(false);  // Admin override করলে
            $table->foreignId('override_by')->nullable()->constrained('users')->nullOnDelete();

            // ভিজিটের তথ্য
            $table->date('visit_date')->nullable();
            $table->decimal('gps_lat', 10, 7)->nullable();
            $table->decimal('gps_lng', 10, 7)->nullable();
            $table->json('photo_paths')->nullable();

            // মূল্যায়ন
            $table->text('household_condition_bn')->nullable();
            $table->boolean('income_verified')->nullable();    // আয়ের তথ্য সঠিক কিনা
            $table->boolean('identity_verified')->nullable();  // পরিচয় মিলে কিনা
            $table->boolean('category_appropriate')->nullable(); // খাত সঠিক কিনা
            $table->enum('recommendation', ['approve', 'reject', 'needs_more_info', 'reduce_amount'])
                  ->default('needs_more_info');
            $table->decimal('recommended_amount', 15, 2)->nullable();
            $table->text('notes_bn')->nullable();

            // রেফারেন্স (ঐচ্ছিক)
            $table->string('up_reference_name')->nullable();   // ইউপি চেয়ারম্যান/মেম্বার
            $table->string('up_reference_mobile')->nullable();
            $table->string('imam_reference_name')->nullable();
            $table->string('neighbor_reference_name')->nullable();
            $table->string('neighbor_reference_mobile')->nullable();

            // অবস্থা
            $table->enum('status', ['submitted', 'reviewed', 'approved', 'rejected'])->default('submitted');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['beneficiary_id', 'status']);
            $table->index(['verifier_id', 'verifier_type']);
            $table->index(['verified_area_id']);
        });

        // ── বিতরণোত্তর ফলো-আপ ─────────────────────────────────────────────
        Schema::create('zakat_followups', function (Blueprint $table) {
            $table->id();
            $table->string('followup_no', 30)->unique();        // FOL-2026-00001
            $table->foreignId('disbursement_id')->constrained('disbursements')->cascadeOnDelete();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('distribution_id')->constrained()->cascadeOnDelete();

            // ফলো-আপ করেন কে
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->enum('reporter_type', ['volunteer', 'imam', 'muezzin', 'org_admin', 'field_agent'])
                  ->default('imam');
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('imam_muezzin_id')->nullable()->constrained('imam_muezzins')->nullOnDelete();

            // তারিখ
            $table->date('followup_date');
            $table->date('due_date')->nullable();               // কবে ফলো-আপ করতে হবে
            $table->date('next_followup_date')->nullable();

            // অর্থের ব্যবহার
            $table->enum('fund_usage_type', [
                'medicine', 'food', 'debt_repaid', 'education', 'livelihood',
                'housing', 'clothing', 'saved', 'misused', 'partially_misused', 'unknown'
            ])->default('unknown');
            $table->text('fund_usage_description_bn')->nullable();

            // উপকারভোগীর বর্তমান অবস্থা
            $table->enum('current_condition', ['improved', 'same', 'worse'])->default('same');
            $table->text('condition_notes_bn')->nullable();
            $table->boolean('needs_further_support')->default(false);
            $table->text('further_support_reason_bn')->nullable();

            // অপব্যবহার
            $table->boolean('misuse_suspected')->default(false);
            $table->text('misuse_description_bn')->nullable();
            $table->json('photo_evidence_paths')->nullable();

            // অবস্থা
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'action_taken'])->default('submitted');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_notes_bn')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['beneficiary_id', 'status']);
            $table->index(['reporter_id', 'reporter_type']);
            $table->index(['due_date', 'status']);
        });

        // ── অপব্যবহার রিপোর্ট ─────────────────────────────────────────────
        Schema::create('misuse_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_no', 30)->unique();          // MIS-2026-00001
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('disbursement_id')->nullable()->constrained('disbursements')->nullOnDelete();
            $table->foreignId('distribution_id')->nullable()->constrained()->nullOnDelete();

            // কে রিপোর্ট করলেন
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->enum('reporter_type', ['volunteer', 'imam', 'muezzin', 'org_admin',
                                            'field_agent', 'community_member'])->default('imam');
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mosque_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('imam_muezzin_id')->nullable()->constrained('imam_muezzins')->nullOnDelete();

            // অপব্যবহারের বিবরণ
            $table->enum('misuse_type', [
                'gambling', 'luxury_spending', 'wasted', 'false_claim',
                'wrong_category', 'sold_aid', 'gave_to_others', 'other'
            ])->default('other');
            $table->text('description_bn');
            $table->json('evidence_paths')->nullable();         // ছবি/ভিডিও প্রমাণ

            // প্রশাসনিক সিদ্ধান্ত
            $table->enum('status', ['pending', 'investigating', 'confirmed', 'dismissed'])->default('pending');
            $table->enum('admin_decision', [
                'warning', 'temporary_restrict', 'permanent_block', 'cleared', 'referred_to_authority'
            ])->nullable();
            $table->integer('restriction_days')->nullable();    // null = permanent
            $table->text('admin_notes_bn')->nullable();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();

            // উপকারভোগীকে জানানো হবে কিনা (Admin কনফিগারযোগ্য)
            $table->boolean('notify_beneficiary')->default(false);

            $table->timestamps();

            $table->index(['beneficiary_id', 'status']);
            $table->index(['reported_by', 'reporter_type']);
            $table->index(['status', 'admin_decision']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('misuse_reports');
        Schema::dropIfExists('zakat_followups');
        Schema::dropIfExists('zakat_verifications');
        Schema::dropIfExists('imam_muezzins');
    }
};
