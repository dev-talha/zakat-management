<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop orphaned tables from any previous failed run
        Schema::dropIfExists('volunteer_area_restrictions');
        Schema::dropIfExists('volunteers');
        Schema::dropIfExists('organization_validators');
        Schema::dropIfExists('organizations');

        // ── সংগঠন (NGO / Foundation / Mosque Committee) ──────────────────────
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('org_code', 20)->unique();           // AUTO: ORG-2026-00001
            $table->string('name_bn');
            $table->string('name_en')->nullable();
            $table->enum('type', ['national', 'regional', 'district', 'mosque_based', 'local_welfare'])
                  ->default('district');
            $table->string('registration_no')->nullable();      // NGO Affairs Bureau / Society Registration
            $table->string('trade_license_no')->nullable();
            $table->string('ngo_registration_no')->nullable();  // NGOAB reg no (if applicable)
            $table->string('contact_person_name');
            $table->string('contact_mobile', 20);
            $table->string('contact_email')->nullable();
            $table->string('website')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('logo_path')->nullable();

            // ঠিকানা
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('upazila')->nullable();
            $table->text('address')->nullable();
            $table->json('coverage_area_ids')->nullable();       // geographic_areas IDs they operate in

            // সংযুক্তি
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();

            // যাচাই অবস্থা
            $table->enum('status', ['pending', 'under_review', 'verified', 'suspended', 'rejected'])
                  ->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('suspension_reason')->nullable();
            $table->boolean('can_manage_own_fund')->default(false); // নিজস্ব তহবিল পরিচালনার অনুমতি
            $table->boolean('field_visit_completed')->default(false);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'district']);
            $table->index(['type', 'status']);
        });

        // ── সংগঠন যাচাইকারী রোল ─────────────────────────────────────────────
        Schema::create('organization_validators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('scope', ['all', 'district', 'type'])->default('all');
            $table->string('scope_value')->nullable();          // district name or org type
            $table->boolean('is_active')->default(true);
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id']);
        });

        // ── ভলান্টিয়ার নিবন্ধন ──────────────────────────────────────────────
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('volunteer_code', 25)->unique();     // VOL-2026-00001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();

            $table->string('nid_no', 30)->unique();             // NID নম্বর — এটি দিয়ে ডুপ্লিকেট আটকানো হয়
            $table->string('name_bn');
            $table->string('name_en')->nullable();
            $table->string('mobile', 20);
            $table->string('occupation')->nullable();
            $table->text('address_bn')->nullable();

            // ভৌগোলিক কভারেজ — এই এলাকার বাইরে যাচাই করতে পারবেন না
            $table->foreignId('primary_area_id')               // geographic_areas
                  ->constrained('geographic_areas')->cascadeOnDelete();
            $table->enum('coverage_level', ['village', 'ward', 'union', 'upazila'])->default('village');

            // অবস্থা
            $table->enum('status', ['pending', 'active', 'suspended', 'removed'])->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // পরিসংখ্যান
            $table->unsignedInteger('total_verifications')->default(0);
            $table->unsignedInteger('total_followups')->default(0);
            $table->timestamp('last_active_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'status']);
            $table->index(['primary_area_id', 'coverage_level']);
        });

        // ── ভলান্টিয়ারের অতিরিক্ত এলাকা সীমা ──────────────────────────────
        Schema::create('volunteer_area_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('geographic_area_id')->constrained('geographic_areas')->cascadeOnDelete();
            $table->enum('level', ['village', 'ward', 'union', 'upazila'])->default('village');
            $table->boolean('can_verify')->default(true);
            $table->boolean('can_followup')->default(true);
            $table->timestamps();

            $table->unique(['volunteer_id', 'geographic_area_id'], 'vol_area_restrict_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_area_restrictions');
        Schema::dropIfExists('volunteers');
        Schema::dropIfExists('organization_validators');
        Schema::dropIfExists('organizations');
    }
};
