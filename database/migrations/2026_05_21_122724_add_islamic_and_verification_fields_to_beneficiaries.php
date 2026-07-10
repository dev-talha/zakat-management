<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('mobile_banking_provider')->nullable()->after('monthly_income');
            $table->string('mobile_banking_account')->nullable()->after('mobile_banking_provider');
            $table->json('category_specific_data_json')->nullable()->after('mobile_banking_account');
            $table->integer('ai_score')->nullable()->after('vulnerability_score');
            $table->string('ai_verification_status')->nullable()->after('ai_score');
            $table->text('ai_notes')->nullable()->after('ai_verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn([
                'mobile_banking_provider',
                'mobile_banking_account',
                'category_specific_data_json',
                'ai_score',
                'ai_verification_status',
                'ai_notes'
            ]);
        });
    }
};
