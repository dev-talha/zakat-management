// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

/**
 * ZakatLedger — a public, tamper-proof registry of Zakat transaction proofs.
 *
 * Only NON-sensitive fields are stored on-chain:
 *   - transactionId / invoiceId  (e.g. receipt no / payout ref)
 *   - amount in the SMALLEST unit (e.g. BDT paisa: 5000.50 BDT => 500050)
 *   - currency (e.g. "BDT")
 *   - recordHash = keccak256 of the full off-chain Laravel record
 *
 * No names, NIDs, phone numbers, or addresses are ever written to the chain.
 * Etherscan can decode both the input data (function call) and the emitted
 * event once this contract's ABI/source is known/verified.
 */
contract ZakatLedger {
    address public owner;
    uint256 public totalRecords;

    event TransactionRecorded(
        string transactionId,
        uint256 amount,
        string currency,
        bytes32 recordHash,
        uint256 timestamp
    );

    constructor() {
        owner = msg.sender;
    }

    function recordTransaction(
        string calldata transactionId,
        uint256 amount,
        string calldata currency,
        bytes32 recordHash
    ) external {
        totalRecords += 1;
        emit TransactionRecorded(transactionId, amount, currency, recordHash, block.timestamp);
    }
}
