<?php

namespace Database\Seeders;

use App\Models\ModuleSetting;
use Illuminate\Database\Seeder;

/**
 * ModuleSettingsSeeder
 *
 * সব মডিউলের default সেটিংস তৈরি করে।
 * Admin Dashboard থেকে যেকোনো সময় পরিবর্তন করা যাবে।
 */
class ModuleSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // ── General ─────────────────────────────────────────────────────────
            ['module' => 'general', 'key' => 'institution_name_bn',  'value' => 'কেন্দ্রীয় যাকাত ব্যবস্থাপনা প্ল্যাটফর্ম', 'type' => 'string', 'label_bn' => 'প্রতিষ্ঠানের নাম (বাংলা)'],
            ['module' => 'general', 'key' => 'institution_name_en',  'value' => 'Central Zakat Management Platform',         'type' => 'string', 'label_bn' => 'প্রতিষ্ঠানের নাম (ইংরেজি)'],
            ['module' => 'general', 'key' => 'portal_url',           'value' => config('app.url', 'https://zakat.example.bd'),'type' => 'string', 'label_bn' => 'পোর্টালের URL'],
            ['module' => 'general', 'key' => 'default_locale',       'value' => 'bn',                                         'type' => 'string', 'label_bn' => 'ডিফল্ট ভাষা'],
            ['module' => 'general', 'key' => 'fiscal_year_month',    'value' => 1,                                            'type' => 'integer','label_bn' => 'অর্থবছরের শুরুর মাস'],
            ['module' => 'general', 'key' => 'ramadan_mode_enabled', 'value' => false,                                        'type' => 'boolean','label_bn' => 'রমজান মোড'],

            // ── Fraud Guard ──────────────────────────────────────────────────────
            ['module' => 'fraud_guard', 'key' => 'enabled',                        'value' => true,  'type' => 'boolean', 'label_bn' => 'ফ্রড সুরক্ষা মডিউল চালু'],
            ['module' => 'fraud_guard', 'key' => 'dedup_nid_check',                'value' => true,  'type' => 'boolean', 'label_bn' => 'NID ডুপ্লিকেট চেক'],
            ['module' => 'fraud_guard', 'key' => 'dedup_mobile_check',             'value' => true,  'type' => 'boolean', 'label_bn' => 'মোবাইল ডুপ্লিকেট চেক'],
            ['module' => 'fraud_guard', 'key' => 'dedup_fuzzy_name_check',         'value' => true,  'type' => 'boolean', 'label_bn' => 'নামের ফাজি মিল চেক'],
            ['module' => 'fraud_guard', 'key' => 'geo_cluster_check',              'value' => true,  'type' => 'boolean', 'label_bn' => 'GPS ক্লাস্টার চেক'],
            ['module' => 'fraud_guard', 'key' => 'blacklist_check_enabled',        'value' => true,  'type' => 'boolean', 'label_bn' => 'কালো তালিকা চেক'],
            ['module' => 'fraud_guard', 'key' => 'dedup_geo_radius_meters',        'value' => 50,    'type' => 'integer', 'label_bn' => 'GPS ডুপ্লিকেট রেডিয়াস (মিটার)'],
            ['module' => 'fraud_guard', 'key' => 'dedup_geo_max_applications',     'value' => 3,     'type' => 'integer', 'label_bn' => 'একই এলাকায় সর্বোচ্চ আবেদন'],
            ['module' => 'fraud_guard', 'key' => 'risk_score_review_threshold',    'value' => 60,    'type' => 'integer', 'label_bn' => 'ম্যানুয়াল রিভিউ থ্রেশহোল্ড (০-১০০)'],
            ['module' => 'fraud_guard', 'key' => 'risk_score_auto_block_threshold','value' => 85,    'type' => 'integer', 'label_bn' => 'অটো-হোল্ড থ্রেশহোল্ড (০-১০০)'],
            ['module' => 'fraud_guard', 'key' => 'auto_hold_on_high_risk',         'value' => true,  'type' => 'boolean', 'label_bn' => 'হাই রিস্কে অটো-হোল্ড'],
            ['module' => 'fraud_guard', 'key' => 'agent_collusion_detection',      'value' => true,  'type' => 'boolean', 'label_bn' => 'এজেন্ট কলিউশন সনাক্তকরণ'],

            // ── Porichoy (NID Verification) — DEFAULT DISABLED ──────────────────
            ['module' => 'porichoy', 'key' => 'enabled',         'value' => false,  'type' => 'boolean', 'label_bn' => 'পরিচয় NID যাচাই মডিউল চালু', 'description_bn' => 'Default বন্ধ। চালু করতে API key দিন।'],
            ['module' => 'porichoy', 'key' => 'api_key',         'value' => '',     'type' => 'string',  'label_bn' => 'API Key',        'sensitive' => true],
            ['module' => 'porichoy', 'key' => 'verify_on_register','value' => false,'type' => 'boolean', 'label_bn' => 'নিবন্ধনে স্বয়ংক্রিয় যাচাই'],
            ['module' => 'porichoy', 'key' => 'block_on_mismatch','value' => false, 'type' => 'boolean', 'label_bn' => 'মিলে না গেলে আবেদন আটকে দাও'],

            // ── SMS Gateway (sms.net.bd) ─────────────────────────────────────────
            ['module' => 'sms_gateway', 'key' => 'enabled',      'value' => true,         'type' => 'boolean', 'label_bn' => 'SMS গেটওয়ে চালু'],
            ['module' => 'sms_gateway', 'key' => 'provider',     'value' => 'sms_net_bd', 'type' => 'string',  'label_bn' => 'প্রোভাইডার'],
            ['module' => 'sms_gateway', 'key' => 'api_key',      'value' => '',           'type' => 'string',  'label_bn' => 'API Key', 'sensitive' => true],
            ['module' => 'sms_gateway', 'key' => 'sender_id',    'value' => 'ZAKAT',      'type' => 'string',  'label_bn' => 'Sender ID'],
            ['module' => 'sms_gateway', 'key' => 'status_check_keyword','value' => 'STATUS','type' => 'string','label_bn' => 'SMS স্ট্যাটাস কীওয়ার্ড'],

            // ── Payment Gateways ─────────────────────────────────────────────────
            ['module' => 'payment', 'key' => 'bkash_enabled',        'value' => true,  'type' => 'boolean', 'label_bn' => 'বিকাশ গেটওয়ে চালু'],
            ['module' => 'payment', 'key' => 'nagad_enabled',        'value' => false, 'type' => 'boolean', 'label_bn' => 'নগদ গেটওয়ে চালু'],
            ['module' => 'payment', 'key' => 'sslcommerz_enabled',   'value' => true,  'type' => 'boolean', 'label_bn' => 'SSLCommerz গেটওয়ে চালু'],
            ['module' => 'payment', 'key' => 'rocket_enabled',       'value' => false, 'type' => 'boolean', 'label_bn' => 'রকেট গেটওয়ে চালু'],
            ['module' => 'payment', 'key' => 'surjopay_enabled',     'value' => false, 'type' => 'boolean', 'label_bn' => 'শুর্জোপে গেটওয়ে চালু'],
            ['module' => 'payment', 'key' => 'sandbox_mode',         'value' => true,  'type' => 'boolean', 'label_bn' => 'স্যান্ডবক্স মোড (টেস্ট)'],

            // ── AI Assistant ─────────────────────────────────────────────────────
            ['module' => 'ai', 'key' => 'enabled',         'value' => false, 'type' => 'boolean', 'label_bn' => 'এআই মডিউল চালু'],
            ['module' => 'ai', 'key' => 'provider',        'value' => 'gemini','type'=> 'string',  'label_bn' => 'এআই প্রোভাইডার'],
            ['module' => 'ai', 'key' => 'ocr_enabled',     'value' => false, 'type' => 'boolean', 'label_bn' => 'OCR চালু'],
            ['module' => 'ai', 'key' => 'risk_scoring',    'value' => false, 'type' => 'boolean', 'label_bn' => 'রিস্ক স্কোরিং চালু'],
            ['module' => 'ai', 'key' => 'translation',     'value' => false, 'type' => 'boolean', 'label_bn' => 'বাংলা-ইংরেজি অনুবাদ চালু'],
            ['module' => 'ai', 'key' => 'monthly_budget_bdt','value'=> 0,    'type' => 'integer', 'label_bn' => 'মাসিক এআই বাজেট (টাকা)'],

            // ── Blockchain ───────────────────────────────────────────────────────
            ['module' => 'blockchain', 'key' => 'enabled',         'value' => false,    'type' => 'boolean', 'label_bn' => 'ব্লকচেইন মডিউল চালু'],
            ['module' => 'blockchain', 'key' => 'network',         'value' => 'sepolia','type' => 'string',  'label_bn' => 'নেটওয়ার্ক'],
            ['module' => 'blockchain', 'key' => 'auto_anchor',     'value' => false,    'type' => 'boolean', 'label_bn' => 'অটো হ্যাশ অ্যাংকর'],

            // ── Field Agent PWA ──────────────────────────────────────────────────
            ['module' => 'field_agent', 'key' => 'enabled',          'value' => true,  'type' => 'boolean', 'label_bn' => 'ফিল্ড এজেন্ট অ্যাপ চালু'],
            ['module' => 'field_agent', 'key' => 'gps_required',     'value' => true,  'type' => 'boolean', 'label_bn' => 'GPS বাধ্যতামূলক'],
            ['module' => 'field_agent', 'key' => 'photo_required',   'value' => true,  'type' => 'boolean', 'label_bn' => 'ছবি তোলা বাধ্যতামূলক'],
            ['module' => 'field_agent', 'key' => 'offline_sync',     'value' => true,  'type' => 'boolean', 'label_bn' => 'অফলাইন সিঙ্ক চালু'],
            ['module' => 'field_agent', 'key' => 'supervisor_review_threshold', 'value' => 60, 'type' => 'integer', 'label_bn' => 'সুপারভাইজার রিভিউ থ্রেশহোল্ড'],

            // ── Mosque Collection ────────────────────────────────────────────────
            ['module' => 'mosque_collection', 'key' => 'enabled',    'value' => true,  'type' => 'boolean', 'label_bn' => 'মসজিদ কালেকশন মডিউল চালু'],
            ['module' => 'mosque_collection', 'key' => 'require_receipt_photo', 'value' => true, 'type' => 'boolean', 'label_bn' => 'রশিদের ছবি বাধ্যতামূলক'],

            // ── Security ─────────────────────────────────────────────────────────
            ['module' => 'security', 'key' => 'mfa_admin_required',  'value' => true,  'type' => 'boolean', 'label_bn' => 'এডমিনের জন্য MFA বাধ্যতামূলক'],
            ['module' => 'security', 'key' => 'mfa_finance_required','value' => true,  'type' => 'boolean', 'label_bn' => 'ফিনান্স টিমের জন্য MFA বাধ্যতামূলক'],
            ['module' => 'security', 'key' => 'login_max_attempts',  'value' => 5,     'type' => 'integer', 'label_bn' => 'সর্বোচ্চ লগইন প্রচেষ্টা'],
            ['module' => 'security', 'key' => 'session_timeout_minutes','value' => 60, 'type' => 'integer', 'label_bn' => 'সেশন মেয়াদ (মিনিট)'],
        ];

        foreach ($settings as $s) {
            ModuleSetting::updateOrCreate(
                ['module_code' => $s['module'], 'setting_key' => $s['key']],
                [
                    'setting_value' => $s['value'],
                    'data_type'     => $s['type'],
                    'label_bn'      => $s['label_bn'] ?? null,
                    'is_sensitive'  => $s['sensitive'] ?? false,
                ]
            );
        }
    }
}
