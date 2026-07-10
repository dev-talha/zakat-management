<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the business fields that are now written on-chain via the
 * ZakatLedger smart contract (amount in smallest unit + currency),
 * plus the contract address and the call method used.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blockchain_anchors', function (Blueprint $table) {
            if (! Schema::hasColumn('blockchain_anchors', 'amount_minor')) {
                $table->unsignedBigInteger('amount_minor')->nullable()->after('reference'); // e.g. paisa
            }
            if (! Schema::hasColumn('blockchain_anchors', 'currency')) {
                $table->string('currency', 10)->nullable()->after('amount_minor');
            }
            if (! Schema::hasColumn('blockchain_anchors', 'contract_address')) {
                $table->string('contract_address', 42)->nullable()->after('from_address');
            }
            if (! Schema::hasColumn('blockchain_anchors', 'method')) {
                $table->string('method', 30)->default('anchor')->after('contract_address'); // anchor | recordTransaction
            }
        });
    }

    public function down(): void
    {
        Schema::table('blockchain_anchors', function (Blueprint $table) {
            $table->dropColumn(['amount_minor', 'currency', 'contract_address', 'method']);
        });
    }
};
