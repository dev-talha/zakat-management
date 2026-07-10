@extends('layouts.app')
@use('App\Models\Setting')

@section('title', 'সিস্টেম সেটিংস')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1"><i class="bi bi-gear-fill me-2 text-primary"></i>সিস্টেম সেটিংস (System Settings)</h2>
            <p class="text-muted mb-0">প্ল্যাটফর্মের মডিউল এবং শরীয়াহ নীতিমালা কনফিগার করুন।</p>
        </div>
    </div>

    <!-- Settings Card -->
    <div class="glass-card shadow">
        <div class="card-header p-0">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs card-header-tabs border-bottom-0 mx-0 px-3" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-3 px-4 border-0" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab"><i class="bi bi-sliders me-2"></i>সাধারণ সেটিংস</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-4 border-0" id="zakat-tab" data-bs-toggle="tab" data-bs-target="#zakat" type="button" role="tab"><i class="bi bi-calculator-fill me-2"></i>যাকাত ও নিসাব</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-4 border-0" id="ai-tab" data-bs-toggle="tab" data-bs-target="#ai" type="button" role="tab"><i class="bi bi-cpu-fill me-2"></i>এআই অ্যাসিস্ট্যান্ট</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-4 border-0" id="blockchain-tab" data-bs-toggle="tab" data-bs-target="#blockchain" type="button" role="tab"><i class="bi bi-shield-fill-check me-2"></i>ব্লকচেইন ইন্টিগ্রিটি</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-4 border-0" id="communication-tab" data-bs-toggle="tab" data-bs-target="#communication" type="button" role="tab"><i class="bi bi-envelope-fill me-2"></i>কমিউনিকেশন (ইমেইল/এসএমএস)</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-4 border-0" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab"><i class="bi bi-credit-card-fill me-2"></i>পেমেন্ট গেটওয়ে</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-4 border-0" id="public-tab" data-bs-toggle="tab" data-bs-target="#public" type="button" role="tab"><i class="bi bi-globe me-2"></i>পাবলিক ওয়েবসাইট</button>
                </li>
            </ul>
        </div>

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div class="card-body p-4">
                <div class="tab-content" id="settingsTabsContent">
                    
                    <!-- General Settings Tab -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <h5 class="text-primary mb-4"><i class="bi bi-info-circle-fill me-2"></i>সাধারণ পরিচিতি ও ভাষা</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">পোর্টালের নাম (বাংলা)</label>
                                <input type="text" name="general__site_name_bn" class="form-control" value="{{ Setting::getValue('general', 'site_name_bn', 'কেন্দ্রীয় যাকাত ব্যবস্থাপনা প্ল্যাটফর্ম') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">পোর্টালের নাম (ইংরেজি)</label>
                                <input type="text" name="general__site_name" class="form-control" value="{{ Setting::getValue('general', 'site_name', 'Central Zakat Management Platform') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ডিফল্ট ভাষা</label>
                                <select name="general__default_locale" class="form-select">
                                    <option value="bn" {{ Setting::getValue('general', 'default_locale', 'bn') == 'bn' ? 'selected' : '' }}>বাংলা</option>
                                    <option value="en" {{ Setting::getValue('general', 'default_locale') == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ডিফল্ট কারেন্সি</label>
                                <input type="text" name="general__default_currency" class="form-control" value="{{ Setting::getValue('general', 'default_currency', 'BDT') }}" required>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="general__show_quick_test_accounts" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="show_quick_test_accounts" name="general__show_quick_test_accounts" value="1" {{ Setting::getValue('general', 'show_quick_test_accounts', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_quick_test_accounts">লগইন পেজে "Quick-Test Accounts" অপশন দেখান</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Zakat & Nisab Tab -->
                    <div class="tab-pane fade" id="zakat" role="tabpanel">
                        <h5 class="text-primary mb-4"><i class="bi bi-bank2 me-2"></i>শরীয়াহ নিসাব ও যাকাত হার</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">স্বর্ণের নিসাব (গ্রাম)</label>
                                <input type="number" step="0.01" name="zakat__nisab_gold_grams" class="form-control" value="{{ Setting::getValue('zakat', 'nisab_gold_grams', 87.48) }}" required>
                                <div class="form-text text-muted">সাধারণত ৮৭.৪৮ গ্রাম (৭.৫ ভরি) স্বর্ণ।</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">রৌপ্যের নিসাব (গ্রাম)</label>
                                <input type="number" step="0.01" name="zakat__nisab_silver_grams" class="form-control" value="{{ Setting::getValue('zakat', 'nisab_silver_grams', 612.36) }}" required>
                                <div class="form-text text-muted">সাধারণত ৬১২.৩৬ গ্রাম (৫২.৫ ভরি) রৌপ্য।</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ডিফল্ট নিসাব ভিত্তি</label>
                                <select name="zakat__default_nisab_basis" class="form-select">
                                    <option value="silver" {{ Setting::getValue('zakat', 'default_nisab_basis', 'silver') == 'silver' ? 'selected' : '' }}>রৌপ্য (দরিদ্র বান্ধব)</option>
                                    <option value="gold" {{ Setting::getValue('zakat', 'default_nisab_basis') == 'gold' ? 'selected' : '' }}>স্বর্ণ</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">যাকাত আদায়ের হার (দশমিক)</label>
                                <input type="number" step="0.001" name="zakat__default_rate" class="form-control" value="{{ Setting::getValue('zakat', 'default_rate', 0.025) }}" required>
                                <div class="form-text text-muted">সাধারণত ২.৫% বা ০.০২৫ শতাংশ।</div>
                            </div>
                        </div>
                    </div>

                    <!-- AI Assistant Tab -->
                    <div class="tab-pane fade" id="ai" role="tabpanel">
                        <h5 class="text-primary mb-4"><i class="bi bi-robot me-2"></i>এআই অ্যাসিস্ট্যান্ট কনফিগারেশন</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">এআই প্রোভাইডার (Provider)</label>
                                <select name="ai__default_provider" class="form-select">
                                    <option value="ollama" {{ Setting::getValue('ai', 'default_provider', 'ollama') == 'ollama' ? 'selected' : '' }}>Ollama (অফলাইন/লোকাল)</option>
                                    <option value="gemini" {{ Setting::getValue('ai', 'default_provider') == 'gemini' ? 'selected' : '' }}>Google Gemini</option>
                                    <option value="openai" {{ Setting::getValue('ai', 'default_provider') == 'openai' ? 'selected' : '' }}>OpenAI GPT</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ollama এন্ডপয়েন্ট (Endpoint)</label>
                                <input type="url" name="ai__ollama_endpoint" class="form-control" value="{{ Setting::getValue('ai', 'ollama_endpoint', 'http://localhost:11434') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">OpenAI / Gemini API Key</label>
                                <input type="password" name="ai__api_key" class="form-control" value="{{ Setting::getValue('ai', 'api_key', '') }}" placeholder="Enter API Key">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">এআই ভ্যালিডেশন থ্রেশহোল্ড (AI Validation Threshold)</label>
                                <input type="number" step="0.01" max="1" min="0" name="ai__confidence_threshold" class="form-control" value="{{ Setting::getValue('ai', 'confidence_threshold', '0.85') }}">
                                <div class="form-text text-muted">0.0 to 1.0. Minimum confidence required for AI auto-approval.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Blockchain Tab -->
                    <div class="tab-pane fade" id="blockchain" role="tabpanel">
                        <h5 class="text-primary mb-4"><i class="bi bi-shuffle me-2"></i>ব্লকচেইন পাবলিক লেজার অ্যাংকরিং (Ethereum)</h5>

                        {{-- Flash results from a test broadcast --}}
                        @if(session('bc_success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-1"></i> {{ session('bc_success') }}
                            @if(session('bc_tx'))
                            <div class="mt-2 small"><strong>Tx:</strong> <code>{{ session('bc_tx') }}</code></div>
                            <a href="{{ session('bc_explorer') }}" target="_blank" class="btn btn-sm btn-success mt-2"><i class="bi bi-box-arrow-up-right me-1"></i>View on Etherscan</a>
                            @endif
                        </div>
                        @endif
                        @if(session('bc_error'))
                        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('bc_error') }}</div>
                        @endif

                        {{-- Live network status --}}
                        <div class="card border-0 mb-4" style="background:rgba(var(--czm-primary-rgb),0.04);">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="bi bi-broadcast me-2 text-primary"></i>Live Network Status</h6>
                                @if(($blockchainStatus['ok'] ?? false))
                                <div class="row g-3 small">
                                    <div class="col-6 col-md-3"><div class="text-muted">Chain</div><div class="fw-bold">{{ $blockchainStatus['chain_id'] }} {{ $blockchainStatus['is_sepolia'] ? '(Sepolia ✓)' : '' }}</div></div>
                                    <div class="col-6 col-md-3"><div class="text-muted">Latest Block</div><div class="fw-bold">{{ number_format($blockchainStatus['block']) }}</div></div>
                                    <div class="col-6 col-md-3"><div class="text-muted">Balance</div><div class="fw-bold">{{ $blockchainStatus['balance_eth'] }} ETH</div></div>
                                    <div class="col-6 col-md-3"><div class="text-muted">Tx Nonce</div><div class="fw-bold">{{ $blockchainStatus['nonce'] }}</div></div>
                                    <div class="col-12"><div class="text-muted">Sending</div>
                                        <span class="badge bg-{{ $blockchainStatus['can_send'] ? 'success' : 'secondary' }}">{{ $blockchainStatus['can_send'] ? 'Private key set — ready to broadcast' : 'No private key — read-only' }}</span>
                                        <span class="badge bg-{{ $blockchainStatus['enabled'] ? 'success' : 'warning text-dark' }}">{{ $blockchainStatus['enabled'] ? 'Anchoring ON' : 'Anchoring OFF' }}</span>
                                    </div>
                                </div>
                                @else
                                <div class="text-danger small"><i class="bi bi-x-circle me-1"></i>Cannot reach network: {{ $blockchainStatus['error'] ?? 'unknown error' }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label d-block">ব্লকচেইন ইন্টিগ্রেশন অবস্থা</label>
                                <div class="form-check form-switch pt-2">
                                    <input type="hidden" name="blockchain__enabled" value="0">
                                    <input class="form-check-input bg-secondary border-0" type="checkbox" name="blockchain__enabled" value="1" id="blockchainSwitch" {{ Setting::getValue('blockchain', 'enabled', false) ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="blockchainSwitch">পাবলিক অ্যাংকরিং চালু করুন (donations/disbursements auto-anchor)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ethereum নেটওয়ার্ক</label>
                                <select name="blockchain__network" class="form-select">
                                    <option value="sepolia" {{ Setting::getValue('blockchain', 'network', 'sepolia') == 'sepolia' ? 'selected' : '' }}>Sepolia Testnet</option>
                                    <option value="mainnet" {{ Setting::getValue('blockchain', 'network') == 'mainnet' ? 'selected' : '' }}>Mainnet</option>
                                    <option value="polygon" {{ Setting::getValue('blockchain', 'network') == 'polygon' ? 'selected' : '' }}>Polygon (PoS)</option>
                                    <option value="private" {{ Setting::getValue('blockchain', 'network') == 'private' ? 'selected' : '' }}>Private Hybrid Network</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">অ্যাকাউন্ট অ্যাড্রেস (Public Address)</label>
                                <input type="text" name="blockchain__account" class="form-control font-monospace" value="{{ Setting::getValue('blockchain', 'account', config('blockchain.account')) }}" placeholder="0x...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">প্রাইভেট কী (Private Key) <span class="text-danger">🔒</span></label>
                                <input type="password" name="blockchain__private_key" class="form-control" autocomplete="new-password"
                                    placeholder="{{ Setting::getValue('blockchain', 'private_key') ? '•••••••• (set — leave blank to keep)' : 'Enter Sepolia test private key' }}">
                                <div class="form-text text-muted">MetaMask → Account details → Show private key. Testnet key only. Never shared/committed.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">RPC URL</label>
                                <input type="url" name="blockchain__rpc_url" class="form-control" value="{{ Setting::getValue('blockchain', 'rpc_url', config('blockchain.rpc_url')) }}" placeholder="https://ethereum-sepolia-rpc.publicnode.com">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Chain ID</label>
                                <input type="number" name="blockchain__chain_id" class="form-control" value="{{ Setting::getValue('blockchain', 'chain_id', config('blockchain.chain_id')) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gas Limit</label>
                                <input type="number" name="blockchain__gas_limit" class="form-control" value="{{ Setting::getValue('blockchain', 'gas_limit', config('blockchain.gas_limit')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Explorer URL</label>
                                <input type="url" name="blockchain__explorer" class="form-control" value="{{ Setting::getValue('blockchain', 'explorer', config('blockchain.explorer')) }}" placeholder="https://sepolia.etherscan.io">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">স্মার্ট কন্ট্রাক্ট অ্যাড্রেস (optional)</label>
                                <input type="text" name="blockchain__contract_address" class="form-control" value="{{ Setting::getValue('blockchain', 'contract_address', '') }}" placeholder="0x...">
                            </div>
                            <div class="col-12">
                                @php $contract = Setting::getValue('blockchain','contract_address', config('blockchain.contract_address')); @endphp
                                @if($contract)
                                <div class="alert alert-success small mb-2"><i class="bi bi-file-earmark-code me-1"></i>ZakatLedger contract deployed: <code>{{ $contract }}</code> — transactions use <code>recordTransaction()</code> (amount, currency, id decodable on Etherscan).</div>
                                @else
                                <div class="alert alert-warning small mb-2"><i class="bi bi-exclamation-triangle me-1"></i>No smart contract deployed yet — transactions will anchor only the record hash. Deploy the contract to record amount/currency/id decodably.</div>
                                @endif
                                <div class="alert alert-light border small mb-2"><i class="bi bi-info-circle me-1"></i>Save settings first (to store the key), then deploy the contract and/or send a test transaction.</div>
                                <button type="submit" class="btn btn-outline-dark me-2" formaction="{{ route('settings.blockchain.deploy') }}" formmethod="POST">
                                    <i class="bi bi-rocket-takeoff me-1"></i>Deploy Contract
                                </button>
                                <button type="submit" class="btn btn-outline-primary" formaction="{{ route('settings.blockchain.test') }}" formmethod="POST">
                                    <i class="bi bi-send-check me-1"></i>Send Test Transaction
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Communication Tab -->
                    <div class="tab-pane fade" id="communication" role="tabpanel">
                        <h5 class="text-primary mb-4"><i class="bi bi-envelope-fill me-2"></i>ইমেইল ও এসএমএস কনফিগারেশন</h5>
                        <div class="row g-4">
                            <div class="col-12"><h6 class="border-bottom pb-2">SMTP ইমেইল সেটিংস</h6></div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Host</label>
                                <input type="text" name="mail__host" class="form-control" value="{{ Setting::getValue('mail', 'host', 'smtp.mailtrap.io') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Port</label>
                                <input type="text" name="mail__port" class="form-control" value="{{ Setting::getValue('mail', 'port', '2525') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Username</label>
                                <input type="text" name="mail__username" class="form-control" value="{{ Setting::getValue('mail', 'username', '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Password</label>
                                <input type="password" name="mail__password" class="form-control" value="{{ Setting::getValue('mail', 'password', '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Encryption (tls/ssl)</label>
                                <input type="text" name="mail__encryption" class="form-control" value="{{ Setting::getValue('mail', 'encryption', 'tls') }}">
                            </div>
                            
                            <div class="col-12 mt-5"><h6 class="border-bottom pb-2">এসএমএস গেটওয়ে সেটিংস</h6></div>
                            <div class="col-md-6">
                                <label class="form-label">SMS Provider</label>
                                <select name="sms__provider" class="form-select">
                                    <option value="none" {{ Setting::getValue('sms', 'provider', 'none') == 'none' ? 'selected' : '' }}>None (Disabled)</option>
                                    <option value="twilio" {{ Setting::getValue('sms', 'provider') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                    <option value="sslwireless" {{ Setting::getValue('sms', 'provider') == 'sslwireless' ? 'selected' : '' }}>SSL Wireless (BD)</option>
                                    <option value="bulkbd" {{ Setting::getValue('sms', 'provider') == 'bulkbd' ? 'selected' : '' }}>Bulk SMS BD</option>
                                    <option value="smsbd" {{ Setting::getValue('sms', 'provider') == 'smsbd' ? 'selected' : '' }}>sms.bd Gateway</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">API Key / Auth Token</label>
                                <input type="password" name="sms__api_key" class="form-control" value="{{ Setting::getValue('sms', 'api_key', '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sender ID / From Number</label>
                                <input type="text" name="sms__sender_id" class="form-control" value="{{ Setting::getValue('sms', 'sender_id', '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Gateways Tab -->
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-primary mb-0"><i class="bi bi-credit-card-fill me-2"></i>পেমেন্ট গেটওয়ে সেটিংস</h5>
                            <div class="form-check form-switch pt-2">
                                <input type="hidden" name="payment__sandbox_mode" value="0">
                                <input class="form-check-input bg-warning border-0" type="checkbox" name="payment__sandbox_mode" value="1" id="sandboxSwitch" {{ Setting::getValue('payment', 'sandbox_mode', true) ? 'checked' : '' }}>
                                <label class="form-check-label text-warning" for="sandboxSwitch"><i class="bi bi-bug me-1"></i> Sandbox (Test Mode)</label>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-12"><h6 class="border-bottom pb-2"><img src="https://scripts.sandbox.bka.sh/favicon.ico" width="20" class="me-2 rounded">bKash Configuration</h6></div>
                            <div class="col-md-4">
                                <label class="form-label">App Key</label>
                                <input type="text" name="payment__bkash_app_key" class="form-control" value="{{ Setting::getValue('payment', 'bkash_app_key', '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">App Secret</label>
                                <input type="password" name="payment__bkash_app_secret" class="form-control" value="{{ Setting::getValue('payment', 'bkash_app_secret', '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Username</label>
                                <input type="text" name="payment__bkash_username" class="form-control" value="{{ Setting::getValue('payment', 'bkash_username', '') }}">
                            </div>

                            <div class="col-12 mt-4"><h6 class="border-bottom pb-2"><img src="https://nagad.com.bd/favicon.ico" width="20" class="me-2 rounded">Nagad Configuration</h6></div>
                            <div class="col-md-4">
                                <label class="form-label">Merchant ID</label>
                                <input type="text" name="payment__nagad_merchant_id" class="form-control" value="{{ Setting::getValue('payment', 'nagad_merchant_id', '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Merchant Number</label>
                                <input type="text" name="payment__nagad_merchant_number" class="form-control" value="{{ Setting::getValue('payment', 'nagad_merchant_number', '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Public/Private Key</label>
                                <input type="password" name="payment__nagad_key" class="form-control" value="{{ Setting::getValue('payment', 'nagad_key', '') }}" placeholder="Key configuration">
                            </div>

                            <div class="col-12 mt-4"><h6 class="border-bottom pb-2"><i class="bi bi-bank me-2 text-primary"></i>SSLCommerz Configuration</h6></div>
                            <div class="col-md-6">
                                <label class="form-label">Store ID</label>
                                <input type="text" name="payment__ssl_store_id" class="form-control" value="{{ Setting::getValue('payment', 'ssl_store_id', '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Store Password</label>
                                <input type="password" name="payment__ssl_store_password" class="form-control" value="{{ Setting::getValue('payment', 'ssl_store_password', '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Public Portal Tab -->
                    <div class="tab-pane fade" id="public" role="tabpanel">
                        <h5 class="text-primary mb-4"><i class="bi bi-globe me-2"></i>পাবলিক ওয়েবসাইট কাস্টমাইজেশন</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">ওয়েবসাইটের শিরোনাম (বাংলা)</label>
                                <input type="text" name="public__site_title_bn" class="form-control" value="{{ Setting::getValue('public', 'site_title_bn', 'কেন্দ্রীয় যাকাত ব্যবস্থাপনা প্ল্যাটফর্ম') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ওয়েবসাইটের শিরোনাম (ইংরেজি)</label>
                                <input type="text" name="public__site_title" class="form-control" value="{{ Setting::getValue('public', 'site_title', 'Central Zakat Management Platform') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">হিরো শিরোনাম (বাংলা)</label>
                                <input type="text" name="public__hero_title_bn" class="form-control" value="{{ Setting::getValue('public', 'hero_title_bn', 'কেন্দ্রীয় যাকাত ব্যবস্থাপনা প্ল্যাটফর্ম') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">হিরো শিরোনাম (ইংরেজি)</label>
                                <input type="text" name="public__hero_title" class="form-control" value="{{ Setting::getValue('public', 'hero_title', 'Central Zakat Management Platform') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">হিরো উপ-শিরোনাম (বাংলা)</label>
                                <textarea name="public__hero_subtitle_bn" rows="2" class="form-control" required>{{ Setting::getValue('public', 'hero_subtitle_bn', 'ডিজিটাল শরীয়াহ্ পালন, এআই যাচাইকরণ ও ব্লকচেইন স্বচ্ছতার সাথে যাকাত সংগ্রহ ও বিতরণ।') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">হিরো উপ-শিরোনাম (ইংরেজি)</label>
                                <textarea name="public__hero_subtitle" rows="2" class="form-control" required>{{ Setting::getValue('public', 'hero_subtitle', 'Digital Shariah compliance, automated AI screening, and blockchain immutability for transparent Zakat collection and distribution.') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">আমাদের সম্পর্কে বর্ণনা (বাংলা)</label>
                                <textarea name="public__about_zakat_bn" rows="4" class="form-control" required>{{ Setting::getValue('public', 'about_zakat_bn', 'কেন্দ্রীয় যাকাত ব্যবস্থাপনা প্ল্যাটফর্ম বাংলাদেশের প্রতিটি ধর্মপ্রাণ মুসলমানকে শরীয়াহ্ সম্মত উপায়ে যাকাত হিসাব, প্রদান ও বিতরণ করার নিরাপত্তা নিশ্চিত করে। আমরা যাকাতদাতাদের এবং প্রকৃত হকদারদের মধ্যে একটি স্বচ্ছ, নিরাপদ ও সুরক্ষিত প্রযুক্তিগত সেতু গঠন করি।') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">আমাদের সম্পর্কে বর্ণনা (ইংরেজি)</label>
                                <textarea name="public__about_zakat" rows="4" class="form-control" required>{{ Setting::getValue('public', 'about_zakat', 'The Central Zakat Management Platform guarantees secure, transparent, and Shariah-compliant Zakat calculation, collection, and payout processing for citizens. We form an immutable cryptographic bridge between generous donors and deserving beneficiaries.') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">শরীয়াহ্ বোর্ড নোটিশ (বাংলা)</label>
                                <textarea name="public__shariah_fatwa_bn" rows="3" class="form-control" required>{{ Setting::getValue('public', 'shariah_fatwa_bn', 'যাকাত ইসলামের একটি রুকন। যাকাত সঠিক খাতে পৌঁছানো ও বন্টন প্রক্রিয়া স্বচ্ছ রাখা যাকাতদাতার অন্যতম দায়িত্ব। এই ডিজিটাল প্ল্যাটফর্মের সকল মডিউল আমাদের সম্মানিত শরীয়াহ্ উপদেষ্টা প্যানেল দ্বারা পরীক্ষিত ও অনুমোদিত।') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">শরীয়াহ্ বোর্ড নোটিশ (ইংরেজি)</label>
                                <textarea name="public__shariah_fatwa" rows="3" class="form-control" required>{{ Setting::getValue('public', 'shariah_fatwa', 'Zakat is an obligatory pillar of Islam. Ensuring it reaches valid categories transparently is a core duty of every Zakat donor. All platform components and ledgers are fully verified and approved by our advisory panel.') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <!-- Submit Button Footer -->
            <div class="card-footer d-flex justify-content-end py-3 px-4 border-top">
                <button type="submit" class="btn btn-czm-primary px-4 py-2"><i class="bi bi-save me-2"></i>সেটিংস সংরক্ষণ করুন</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    #settingsTabs .nav-link {
        border-radius: 0;
        background-color: transparent !important;
        transition: all 0.2s ease-in-out;
        color: var(--czm-text-secondary);
    }
    #settingsTabs .nav-link.active {
        border-bottom: 3px solid var(--czm-primary) !important;
        font-weight: 600;
        color: var(--czm-primary) !important;
        background-color: rgba(var(--czm-primary-rgb), 0.05) !important;
    }
    #settingsTabs .nav-link:hover:not(.active) {
        background-color: rgba(var(--czm-primary-rgb), 0.02) !important;
        color: var(--czm-text-primary);
    }
</style>
@endpush
