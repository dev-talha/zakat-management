<?php

return [
    // Master switch. When false, the app records anchors as "skipped"
    // and never reaches out to the network.
    'enabled' => env('BLOCKCHAIN_ENABLED', false),

    'network' => env('BLOCKCHAIN_NETWORK', 'sepolia'),

    // JSON-RPC endpoint (public Sepolia node by default).
    'rpc_url' => env('BLOCKCHAIN_RPC_URL', 'https://ethereum-sepolia-rpc.publicnode.com'),

    // Sepolia = 11155111 (0xaa36a7).
    'chain_id' => (int) env('BLOCKCHAIN_CHAIN_ID', 11155111),

    // Sending account (public address). Anchors are self-transactions
    // carrying the record hash in the data field.
    'account' => env('BLOCKCHAIN_ACCOUNT', '0x1D8d70D52A8DC2d289f4329bd70E8D33C5Dff479'),

    // Private key of the account — SECRET. Keep only in .env, never commit.
    // Without it, only read operations work.
    'private_key' => env('BLOCKCHAIN_PRIVATE_KEY', ''),

    // Deployed ZakatLedger smart contract address (set after deployment).
    'contract_address' => env('BLOCKCHAIN_CONTRACT_ADDRESS', ''),

    // Gas limit for a simple data-carrying self transaction.
    'gas_limit' => (int) env('BLOCKCHAIN_GAS_LIMIT', 30000),

    'explorer' => env('BLOCKCHAIN_EXPLORER', 'https://sepolia.etherscan.io'),
];
