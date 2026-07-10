<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ৭টি শরিয়াহ জাকাত খাত (Al-Amilina system role মাত্র, সাধারণ আবেদনে নেই)
        Schema::create('zakat_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();       // e.g. 'fuqara', 'masakin', 'gharimin'
            $table->string('arabic_name', 100);
            $table->string('name_bn');                  // বাংলা নাম
            $table->string('name_en');
            $table->text('description_bn')->nullable();
            $table->text('description_en')->nullable();
            $table->text('eligibility_criteria_bn')->nullable(); // Admin-editable eligibility text
            $table->string('icon_class', 60)->nullable();        // e.g. 'fas fa-hand-holding-heart'
            $table->string('color_hex', 10)->default('#10B981'); // Category card color
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_field_visit')->default(true);
            $table->boolean('requires_shariah_review')->default(false);
            $table->timestamps();
        });

        // প্রতিটি খাতের জন্য ডাইনামিক ফর্ম ফিল্ড — Admin থেকে পরিবর্তনযোগ্য
        Schema::create('zakat_category_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zakat_category_id')->constrained()->cascadeOnDelete();
            $table->string('field_key', 80);            // Unique key within category
            $table->string('label_bn');
            $table->string('label_en')->nullable();
            $table->string('placeholder_bn')->nullable();
            $table->enum('field_type', [
                'text', 'textarea', 'number', 'decimal', 'date',
                'select', 'radio', 'checkbox', 'file', 'phone', 'nid'
            ])->default('text');
            $table->json('field_options')->nullable();   // For select/radio: [{"value":"x","label_bn":"য"}]
            $table->boolean('is_required')->default(false);
            $table->string('validation_rules')->nullable(); // Laravel validation rules string
            $table->text('help_text_bn')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['zakat_category_id', 'field_key']);
            $table->index(['zakat_category_id', 'sort_order']);
        });

        // প্রতিটি খাতের জন্য প্রয়োজনীয় ডকুমেন্টের তালিকা — Admin কনফিগারযোগ্য
        Schema::create('zakat_category_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zakat_category_id')->constrained()->cascadeOnDelete();
            $table->string('doc_key', 80);              // e.g. 'nid_front', 'debt_proof', 'medical_report'
            $table->string('label_bn');
            $table->string('label_en')->nullable();
            $table->text('description_bn')->nullable();
            $table->boolean('is_required')->default(true);
            $table->string('accepted_mime_types')->default('image/jpeg,image/png,application/pdf');
            $table->integer('max_size_kb')->default(2048); // 2MB default
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['zakat_category_id', 'doc_key']);
        });

        // উপকারভোগীর category-specific ডাইনামিক ডেটা সংরক্ষণ
        Schema::create('beneficiary_category_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('zakat_category_id')->constrained()->cascadeOnDelete();
            $table->json('form_data');                   // { "field_key": "value", ... }
            $table->enum('verification_status', ['unverified', 'verified', 'rejected'])->default('unverified');
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['beneficiary_id', 'zakat_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiary_category_data');
        Schema::dropIfExists('zakat_category_documents');
        Schema::dropIfExists('zakat_category_forms');
        Schema::dropIfExists('zakat_categories');
    }
};
