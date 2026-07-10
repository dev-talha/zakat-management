<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile', 20)->nullable()->unique()->after('email');
            $table->string('name_bn')->nullable()->after('name');
            $table->string('avatar')->nullable()->after('mobile');
            $table->foreignId('branch_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->enum('user_type', ['staff', 'donor', 'beneficiary', 'agent'])->default('staff')->after('avatar');
            $table->string('locale', 5)->default('bn')->after('user_type');
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending'])->default('active')->after('locale');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn([
                'mobile', 'name_bn', 'avatar', 'branch_id',
                'user_type', 'locale', 'status', 'last_login_at', 'last_login_ip'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
