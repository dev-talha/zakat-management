// Compiles contracts/ZakatLedger.sol -> database/data/ZakatLedger.json {abi, bytecode}
const fs = require('fs');
const path = require('path');
const solc = require('solc');

const src = fs.readFileSync(path.join(__dirname, 'ZakatLedger.sol'), 'utf8');

const input = {
  language: 'Solidity',
  sources: { 'ZakatLedger.sol': { content: src } },
  settings: {
    optimizer: { enabled: true, runs: 200 },
    outputSelection: { '*': { '*': ['abi', 'evm.bytecode.object'] } },
  },
};

const out = JSON.parse(solc.compile(JSON.stringify(input)));
if (out.errors) {
  const fatal = out.errors.filter((e) => e.severity === 'error');
  out.errors.forEach((e) => console.log(e.formattedMessage));
  if (fatal.length) process.exit(1);
}

const c = out.contracts['ZakatLedger.sol']['ZakatLedger'];
const artifact = { abi: c.abi, bytecode: '0x' + c.evm.bytecode.object };

const destDir = path.join(__dirname, '..', 'database', 'data');
fs.mkdirSync(destDir, { recursive: true });
const dest = path.join(destDir, 'ZakatLedger.json');
fs.writeFileSync(dest, JSON.stringify(artifact, null, 2));
console.log('Wrote', dest);
console.log('bytecode length:', artifact.bytecode.length);
