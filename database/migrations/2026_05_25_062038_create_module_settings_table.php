<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_settings', function (Blueprint $table) {
            $table->id();
            $table->string('module_code', 60);          // e.g. 'fraud_guard', 'porichoy', 'blockchain'
            $table->string('setting_key', 100);         // e.g. 'enabled', 'dedup_nid_check', 'risk_threshold'
            $table->json('setting_value');              // bool, int, string, array all stored as json
            $table->enum('data_type', ['boolean', 'integer', 'string', 'json', 'enum', 'float'])
                  ->default('string');
            $table->string('label_bn')->nullable();     // Bangla label for admin UI
            $table->string('label_en')->nullable();     // English label
            $table->text('description_bn')->nullable(); // Help text in Bangla
            $table->string('group_label_bn')->nullable();
            $table->boolean('is_sensitive')->default(false); // Mask value in UI if true (secrets)
            $table->boolean('is_public')->default(false);    // Expose to frontend JS config
            $table->integer('sort_order')->default(0);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['module_code', 'setting_key']);
            $table->index(['module_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_settings');
    }
};
