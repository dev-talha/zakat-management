<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop orphaned tables from any previous failed run
        Schema::dropIfExists('distribution_queues');
        Schema::dropIfExists('distribution_rounds');
        Schema::dropIfExists('beneficiary_support_history');
        Schema::dropIfExists('support_policies');

        // ── বারবার সহায়তার নিয়মাবলী — Admin কনফিগারযোগ্য ─────────────────
        Schema::create('support_policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_code', 40)->unique();        // one_time, annual, long_term, emergency
            $table->string('name_bn');
            $table->string('name_en');
            $table->text('description_bn')->nullable();

            $table->enum('support_type', ['one_time', 'annual', 'biannual', 'quarterly', 'long_term', 'emergency'])
                  ->default('one_time');
            $table->unsignedInteger('min_interval_days')->default(365); // ন্যূনতম ব্যবধান (দিন)
            $table->unsignedInteger('max_times_per_year')->default(1);  // বছরে সর্বোচ্চ কতবার
            $table->unsignedInteger('max_consecutive_years')->nullable(); // সর্বোচ্চ কত বছর ধারাবাহিকভাবে

            // শর্তাবলী
            $table->boolean('requires_followup_before_reapply')->default(true);  // পুনঃআবেদনে ফলো-আপ বাধ্যতামূলক
            $table->boolean('requires_imam_recommendation')->default(false);      // ইমামের সুপারিশ লাগবে
            $table->boolean('requires_new_verification')->default(true);          // নতুন করে যাচাই করতে হবে
            $table->boolean('auto_schedule_followup')->default(true);             // ফলো-আপ অটো সিডিউল হবে
            $table->unsignedInteger('followup_after_days')->default(30);          // কত দিন পরে ফলো-আপ

            // পুনঃযাচাই
            $table->unsignedInteger('auto_review_interval_days')->default(180);   // কত দিন পরে যোগ্যতা পুনর্বিবেচনা
            $table->json('applicable_zakat_categories')->nullable();              // কোন খাতে প্রযোজ্য

            // Priority Penalty — আগে পাওয়া থাকলে Score কমবে
            $table->integer('priority_penalty_per_receipt')->default(-10);        // প্রতিবার পেলে Score কমে
            $table->integer('priority_penalty_max')->default(-30);                // সর্বোচ্চ কত কমবে

            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ── উপকারভোগীর সহায়তার ইতিহাস ──────────────────────────────────
        Schema::create('beneficiary_support_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('distribution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('disbursement_id')->nullable()->constrained('disbursements')->nullOnDelete();
            $table->foreignId('policy_id')->nullable()->constrained('support_policies')->nullOnDelete();
            $table->foreignId('zakat_category_id')->nullable()->constrained()->nullOnDelete();

            $table->string('zakat_category_code', 30)->nullable();
            $table->decimal('amount_received', 15, 2)->default(0);
            $table->date('distribution_date');
            $table->string('payment_method')->nullable();       // bkash, cash, bank

            // পরবর্তী আবেদনের যোগ্যতা
            $table->date('next_eligible_date')->nullable();     // কবে থেকে আবার আবেদন করতে পারবেন
            $table->boolean('followup_required')->default(true);
            $table->date('followup_due_date')->nullable();
            $table->enum('followup_status', ['pending', 'completed', 'overdue', 'skipped'])->default('pending');

            // নিষেধাজ্ঞা
            $table->boolean('is_restricted')->default(false);   // অপব্যবহারের কারণে সীমাবদ্ধ
            $table->boolean('is_permanently_blocked')->default(false);
            $table->text('restriction_reason')->nullable();
            $table->timestamp('restriction_expires_at')->nullable(); // null = permanent

            $table->timestamps();

            $table->index(['beneficiary_id', 'distribution_date'], 'bsh_beneficiary_dist_date_index');
            $table->index(['beneficiary_id', 'next_eligible_date'], 'bsh_beneficiary_next_elig_index');
            $table->index(['followup_due_date', 'followup_status'], 'bsh_followup_due_status_index');
        });

        // ── স্মার্ট বিতরণ চক্র ────────────────────────────────────────────
        Schema::create('distribution_rounds', function (Blueprint $table) {
            $table->id();
            $table->string('round_no', 30)->unique();           // RND-2026-001
            $table->foreignId('fund_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name_bn')->nullable();              // যেমন: রমজান বিতরণ ২০২৬
            $table->enum('round_type', ['regular', 'ramadan', 'eid', 'flood_emergency', 'special'])
                  ->default('regular');
            $table->decimal('total_fund_available', 15, 2)->default(0);
            $table->decimal('total_distributed', 15, 2)->default(0);
            $table->decimal('reserved_for_emergency', 15, 2)->default(0); // জরুরি রিজার্ভ

            $table->unsignedInteger('total_beneficiaries_planned')->default(0);
            $table->unsignedInteger('total_beneficiaries_served')->default(0);

            $table->date('round_start_date')->nullable();
            $table->date('round_end_date')->nullable();
            $table->enum('status', ['draft', 'open', 'processing', 'completed', 'cancelled'])->default('draft');

            // Distribution Rules (এই চক্রে প্রযোজ্য নিয়ম)
            $table->boolean('prioritize_never_received')->default(true);  // যারা কখনো পায়নি তারা আগে
            $table->boolean('allow_repeat_recipients')->default(true);
            $table->integer('max_per_beneficiary')->default(1);           // এক চক্রে কতবার পাবে
            $table->json('category_allocation_percent')->nullable();       // কোন খাতে কত % বরাদ্দ

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ── অগ্রাধিকার ভিত্তিক বিতরণ লাইন ────────────────────────────────
        Schema::create('distribution_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('zakat_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('distribution_round_id')->nullable()->constrained('distribution_rounds')->nullOnDelete();

            // আবেদনকারী নিজে requested_amount সেট করতে পারবেন
            $table->decimal('requested_amount', 15, 2)->default(0);       // আবেদনকারীর চাহিদা
            $table->decimal('minimum_acceptable_amount', 15, 2)->default(0); // ন্যূনতম গ্রহণযোগ্য পরিমাণ
            $table->decimal('approved_amount', 15, 2)->nullable();         // অনুমোদিত পরিমাণ

            // ── Priority Score (0-100) ──
            $table->decimal('priority_score', 5, 2)->default(0);          // মোট স্কোর
            $table->json('priority_factors')->nullable();                  // স্কোরের বিবরণ JSON

            // ফ্যাক্টর স্কোর (বিস্তারিত)
            $table->decimal('vulnerability_score', 5, 2)->default(0);     // দারিদ্র্য / দুর্বলতা (max 30)
            $table->decimal('category_weight', 5, 2)->default(0);         // খাত ভিত্তিক ওজন (max 20)
            $table->decimal('waiting_time_score', 5, 2)->default(0);      // কতদিন অপেক্ষায় (max 15)
            $table->decimal('urgency_score', 5, 2)->default(0);           // জরুরি প্রয়োজন (max 20)
            $table->decimal('recommendation_score', 5, 2)->default(0);    // ইমাম/ভলান্টিয়ার সুপারিশ (max 10)
            $table->decimal('repeat_receipt_penalty', 5, 2)->default(0);  // আগে পেয়েছেন, পয়েন্ট কাটা (max -15)
            $table->decimal('misuse_history_penalty', 5, 2)->default(0);  // অপব্যবহারের ইতিহাস (max -30)

            // পরিসংখ্যান
            $table->unsignedInteger('previously_received_count')->default(0); // মোট কতবার আগে পেয়েছেন
            $table->date('last_received_date')->nullable();
            $table->unsignedInteger('queue_position')->nullable();         // বর্তমান অবস্থান

            // অবস্থা
            $table->enum('queue_status', [
                'waiting', 'priority_review', 'processing', 'distributed', 'skipped', 'expired', 'cancelled'
            ])->default('waiting');
            $table->timestamp('added_to_queue_at')->useCurrent();
            $table->timestamp('distributed_at')->nullable();
            $table->text('skip_reason')->nullable();

            $table->timestamps();

            $table->index(['distribution_round_id', 'queue_status', 'priority_score'], 'dist_queues_round_status_score_index');
            $table->index(['beneficiary_id', 'queue_status']);
            $table->index(['priority_score', 'previously_received_count'], 'dist_queues_score_prev_count_index');
        });

        // ── প্রাথমিক নীতিমালা ─────────────────────────────────────────────
        // (Seeder দিয়ে পূরণ হবে, এখানে শুধু টেবিল)
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution_queues');
        Schema::dropIfExists('distribution_rounds');
        Schema::dropIfExists('beneficiary_support_history');
        Schema::dropIfExists('support_policies');
    }
};
