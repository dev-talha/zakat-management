<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_requests', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 30);
            $table->string('model', 50);
            $table->string('task_type', 50);
            $table->string('prompt_version', 20)->nullable();
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->integer('token_usage')->default(0);
            $table->decimal('cost_estimate', 10, 6)->default(0);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->json('response_json')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('review_decision', ['accepted', 'modified', 'rejected'])->nullable();
            $table->timestamps();
        });

        Schema::create('ai_risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->json('factors_json')->nullable();
            $table->text('explanation')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('blockchain_records', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('hash', 128);
            $table->string('chain', 20)->default('ethereum');
            $table->string('network', 20)->default('sepolia');
            $table->string('tx_hash')->nullable();
            $table->enum('anchor_status', ['pending', 'anchored', 'failed', 'disabled'])->default('pending');
            $table->timestamps();
            $table->index(['entity_type', 'entity_id']);
            $table->index('hash');
            $table->index('tx_hash');
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no', 30)->unique();
            $table->string('complainant_type')->nullable();
            $table->unsignedBigInteger('complainant_id')->nullable();
            $table->string('complainant_name')->nullable();
            $table->string('complainant_contact')->nullable();
            $table->enum('channel', ['web', 'phone', 'email', 'sms', 'in_person', 'anonymous'])->default('web');
            $table->string('category')->nullable();
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('description');
            $table->timestamp('sla_due_at')->nullable();
            $table->enum('status', ['open', 'assigned', 'investigating', 'resolved', 'closed', 'escalated'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resolution')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('notifications_log', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_type');
            $table->unsignedBigInteger('recipient_id');
            $table->enum('channel', ['email', 'sms', 'whatsapp', 'push'])->default('email');
            $table->string('template_code', 50);
            $table->json('payload_json')->nullable();
            $table->enum('send_status', ['queued', 'sent', 'delivered', 'failed'])->default('queued');
            $table->text('failure_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group_code', 50);
            $table->string('key', 100);
            $table->json('value_json')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['group_code', 'key']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50);
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('before_json')->nullable();
            $table->json('after_json')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->index(['actor_id', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('notifications_log');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('blockchain_records');
        Schema::dropIfExists('ai_risk_scores');
        Schema::dropIfExists('ai_requests');
    }
};
