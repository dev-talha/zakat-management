<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Immutable on-chain audit trail. Each row records a Zakat transaction
 * (donation / disbursement / verification) whose hash was anchored to
 * the Ethereum (Sepolia) network.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blockchain_anchors', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('anchorable');            // Collection / Disbursement / ZakatVerification …
            $table->string('reference')->nullable();          // human ref (receipt_no, payout_ref, …)
            $table->string('payload_hash', 66);               // 0x + keccak256 of the canonical record
            $table->json('payload')->nullable();              // the exact data that was hashed
            $table->string('network', 30)->default('sepolia');
            $table->string('from_address', 42)->nullable();
            $table->string('tx_hash', 66)->nullable();
            $table->unsignedBigInteger('block_number')->nullable();
            $table->enum('status', ['pending', 'sent', 'confirmed', 'failed', 'skipped'])->default('pending');
            $table->string('explorer_url')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'network']);
            $table->index('tx_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blockchain_anchors');
    }
};
