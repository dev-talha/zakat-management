<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use App\Models\Fund;
use App\Models\PaymentGateway;
use App\Models\Setting;
use App\Models\Campaign;
use App\Models\Donor;
use App\Models\DonorAddress;
use App\Models\Collection;
use App\Models\Organization;
use App\Models\Volunteer;
use App\Models\Beneficiary;
use App\Models\BeneficiaryHousehold;
use App\Models\CaseRecord;
use App\Models\Distribution;
use App\Models\Disbursement;
use App\Models\GeographicArea;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create branches
        $dhaka = Branch::create(['code' => 'DHK-001', 'name' => 'Dhaka Central', 'name_bn' => 'ঢাকা কেন্দ্রীয়', 'region' => 'Dhaka', 'division' => 'Dhaka', 'district' => 'Dhaka', 'status' => 'active']);
        $ctg = Branch::create(['code' => 'CTG-001', 'name' => 'Chittagong Branch', 'name_bn' => 'চট্টগ্রাম শাখা', 'region' => 'Chittagong', 'division' => 'Chittagong', 'district' => 'Chittagong', 'status' => 'active']);
        $rajshahi = Branch::create(['code' => 'RAJ-001', 'name' => 'Rajshahi Branch', 'name_bn' => 'রাজশাহী শাখা', 'region' => 'Rajshahi', 'division' => 'Rajshahi', 'district' => 'Rajshahi', 'status' => 'active']);

        // Create Super Admin
        $admin = User::create([
            'name' => 'System Administrator',
            'name_bn' => 'সিস্টেম প্রশাসক',
            'email' => 'admin@czm.bd',
            'mobile' => '01700000001',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'branch_id' => $dhaka->id,
            'locale' => 'en',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('super_admin');

        // Create Finance Admin
        $finance = User::create([
            'name' => 'Finance Manager',
            'name_bn' => 'অর্থ ব্যবস্থাপক',
            'email' => 'finance@czm.bd',
            'mobile' => '01700000002',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'branch_id' => $dhaka->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $finance->assignRole('finance_admin');

        // Create Zakat Officer
        $officer = User::create([
            'name' => 'Zakat Officer',
            'name_bn' => 'যাকাত কর্মকর্তা',
            'email' => 'officer@czm.bd',
            'mobile' => '01700000003',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'branch_id' => $dhaka->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $officer->assignRole('zakat_officer');

        // Create Fund Types
        Fund::create(['code' => 'ZAKAT', 'name' => 'Zakat Fund', 'name_bn' => 'যাকাত তহবিল', 'type' => 'zakat', 'restricted_flag' => true]);
        Fund::create(['code' => 'SADAQAH', 'name' => 'Sadaqah Fund', 'name_bn' => 'সদকা তহবিল', 'type' => 'sadaqah']);
        Fund::create(['code' => 'FITRAH', 'name' => 'Fitrah Fund', 'name_bn' => 'ফিতরা তহবিল', 'type' => 'fitrah', 'restricted_flag' => true]);
        Fund::create(['code' => 'WAQF', 'name' => 'Waqf Fund', 'name_bn' => 'ওয়াক্‌ফ তহবিল', 'type' => 'waqf', 'restricted_flag' => true]);
        Fund::create(['code' => 'EMERGENCY', 'name' => 'Emergency Relief', 'name_bn' => 'জরুরি সাহায্য', 'type' => 'emergency']);
        Fund::create(['code' => 'GENERAL', 'name' => 'General Donation', 'name_bn' => 'সাধারণ দান', 'type' => 'general']);

        // Payment Gateways (sandbox)
        PaymentGateway::create(['code' => 'sslcommerz', 'name' => 'SSLCOMMERZ', 'mode' => 'sandbox', 'active' => true, 'sort_order' => 1, 'config_json' => ['store_id' => '', 'store_passwd' => '', 'sandbox' => true]]);
        PaymentGateway::create(['code' => 'bkash', 'name' => 'bKash', 'mode' => 'sandbox', 'active' => false, 'sort_order' => 2, 'config_json' => ['app_key' => '', 'app_secret' => '', 'sandbox' => true]]);
        PaymentGateway::create(['code' => 'manual', 'name' => 'Manual/Cash', 'mode' => 'live', 'active' => true, 'sort_order' => 10, 'config_json' => []]);

        // Demo Campaigns
        $campaign = Campaign::create([
            'name' => 'Ramadan Zakat Drive 2026', 'name_bn' => 'রমজান যাকাত অভিযান ২০২৬',
            'slug' => 'ramadan-zakat-2026', 'fund_type' => 'zakat', 'target_amount' => 5000000,
            'description' => 'Annual Ramadan zakat collection campaign for poverty alleviation.',
            'description_bn' => 'দারিদ্র্য বিমোচনের জন্য বার্ষিক রমজান যাকাত সংগ্রহ অভিযান।',
            'starts_at' => now(), 'ends_at' => now()->addMonths(2), 'status' => 'active', 'is_featured' => true,
        ]);
        Campaign::create([
            'name' => 'Education Support', 'name_bn' => 'শিক্ষা সহায়তা',
            'slug' => 'education-support', 'fund_type' => 'sadaqah', 'target_amount' => 2000000,
            'description' => 'Supporting underprivileged students with school supplies and tuition fees.',
            'starts_at' => now(), 'ends_at' => now()->addMonths(6), 'status' => 'active',
        ]);

        // System Settings
        Setting::setValue('general', 'site_name', 'Central Zakat Management Platform');
        Setting::setValue('general', 'site_name_bn', 'কেন্দ্রীয় যাকাত ব্যবস্থাপনা প্ল্যাটফর্ম');
        Setting::setValue('general', 'default_currency', 'BDT');
        Setting::setValue('general', 'default_locale', 'bn');
        Setting::setValue('zakat', 'nisab_gold_grams', 87.48);
        Setting::setValue('zakat', 'nisab_silver_grams', 612.36);
        Setting::setValue('zakat', 'default_nisab_basis', 'silver');
        Setting::setValue('zakat', 'default_rate', 0.025);
        Setting::setValue('zakat', 'zakat_categories', [
            'faqir' => ['en' => 'The Poor (Faqir)', 'bn' => 'ফকির'],
            'miskin' => ['en' => 'The Needy (Miskin)', 'bn' => 'মিসকিন'],
            'amil' => ['en' => 'Zakat Administrators (Amil)', 'bn' => 'আমিল'],
            'muallaf' => ['en' => 'New Muslims (Muallaf)', 'bn' => 'মুয়াল্লাফ'],
            'riqab' => ['en' => 'Freeing Captives (Riqab)', 'bn' => 'রিকাব'],
            'gharimin' => ['en' => 'Debtors (Gharimin)', 'bn' => 'গারিমীন'],
            'fisabilillah' => ['en' => 'In the Way of Allah (Fi Sabilillah)', 'bn' => 'ফী সাবিলিল্লাহ'],
            'ibnussabil' => ['en' => 'Wayfarers (Ibnus Sabil)', 'bn' => 'ইবনুস সাবিল'],
        ]);
        Setting::setValue('ai', 'default_provider', 'ollama');
        Setting::setValue('ai', 'ollama_endpoint', 'http://localhost:11434');
        Setting::setValue('blockchain', 'enabled', false);
        Setting::setValue('blockchain', 'network', 'sepolia');

        // Create Geographic Areas
        $divisionArea = GeographicArea::create([
            'level' => 'division',
            'name_bn' => 'ঢাকা বিভাগ',
            'name_en' => 'Dhaka Division',
            'area_type' => 'urban',
            'is_active' => true,
        ]);

        $districtArea = GeographicArea::create([
            'parent_id' => $divisionArea->id,
            'level' => 'district',
            'name_bn' => 'ঢাকা জেলা',
            'name_en' => 'Dhaka District',
            'area_type' => 'urban',
            'is_active' => true,
        ]);

        $villageArea = GeographicArea::create([
            'parent_id' => $districtArea->id,
            'level' => 'village',
            'name_bn' => 'বারিধারা গ্রাম',
            'name_en' => 'Baridhara Village',
            'area_type' => 'urban',
            'is_active' => true,
        ]);

        // ── 1. Create Demo Donor ──
        $donorUser = User::create([
            'name' => 'Demo Donor',
            'name_bn' => 'ডেমো যাকাতদাতা',
            'email' => 'donor@czm.bd',
            'mobile' => '01700000004',
            'password' => bcrypt('password'),
            'user_type' => 'donor',
            'branch_id' => $dhaka->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $donorUser->assignRole('donor');

        $donor = Donor::create([
            'user_id' => $donorUser->id,
            'donor_type' => 'individual',
            'display_name' => 'Al-Karim Trust',
            'legal_name' => 'Abdul Karim',
            'kyc_status' => 'verified',
        ]);

        DonorAddress::create([
            'donor_id' => $donor->id,
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Gulshan',
            'address_line' => 'House 12, Road 4, Baridhara',
        ]);

        // Create some donations (Collections)
        Collection::create([
            'receipt_no' => 'REC-2026-00001',
            'donor_id' => $donor->id,
            'campaign_id' => $campaign->id,
            'branch_id' => $dhaka->id,
            'fund_type' => 'zakat',
            'source_channel' => 'online',
            'amount' => 75000.00,
            'payment_gateway' => 'bkash',
            'gateway_transaction_id' => 'TRX-BK9823471',
            'payment_status' => 'paid',
            'status' => 'validated',
            'notes' => 'General Zakat donation',
        ]);

        Collection::create([
            'receipt_no' => 'REC-2026-00002',
            'donor_id' => $donor->id,
            'campaign_id' => $campaign->id,
            'branch_id' => $dhaka->id,
            'fund_type' => 'sadaqah',
            'source_channel' => 'online',
            'amount' => 15000.00,
            'payment_gateway' => 'sslcommerz',
            'gateway_transaction_id' => 'TRX-SSL348912',
            'payment_status' => 'paid',
            'status' => 'validated',
            'notes' => 'Education Sadaqah donation',
        ]);

        // ── 2. Create Demo Partner Organization ──
        $orgUser = User::create([
            'name' => 'Dhaka Welfare Admin',
            'name_bn' => 'ঢাকা সমাজকল্যাণ প্রশাসক',
            'email' => 'org@czm.bd',
            'mobile' => '01700000005',
            'password' => bcrypt('password'),
            'user_type' => 'org_admin',
            'branch_id' => $dhaka->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $orgUser->assignRole('organization');

        $org = Organization::create([
            'org_code' => Organization::generateCode(),
            'referral_code' => Organization::generateReferralCode(),
            'name_en' => 'Dhaka Welfare Society',
            'name_bn' => 'ঢাকা সমাজকল্যাণ সংস্থা',
            'type' => 'local_welfare',
            'registration_no' => 'REG-NGO-98234',
            'contact_person_name' => 'Mustafa Rahman',
            'contact_mobile' => '01811111111',
            'contact_email' => 'org@czm.bd',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Gulshan',
            'address' => 'Welfare Mansion, Gulshan 2',
            'branch_id' => $dhaka->id,
            'status' => 'verified',
            'verified_by' => $admin->id,
            'verified_at' => now(),
            'total_collected_via_referral' => 125000.00,
            'total_donors_via_referral' => 3,
        ]);

        // ── 3. Create Demo Volunteer ──
        $volUser = User::create([
            'name' => 'Kamrul Hasan',
            'name_bn' => 'কামরুল হাসান',
            'email' => 'volunteer@czm.bd',
            'mobile' => '01700000006',
            'password' => bcrypt('password'),
            'user_type' => 'volunteer',
            'branch_id' => $dhaka->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $volUser->assignRole('volunteer');

        $vol = Volunteer::create([
            'volunteer_code' => Volunteer::generateCode(),
            'referral_code' => Volunteer::generateReferralCode(),
            'user_id' => $volUser->id,
            'organization_id' => $org->id,
            'nid_no' => '3928102391',
            'name_en' => 'Kamrul Hasan',
            'name_bn' => 'কামরুল হাসান',
            'mobile' => '01700000006',
            'occupation' => 'Student',
            'address_bn' => 'ঢাকা, বারিধারা',
            'primary_area_id' => $villageArea->id,
            'coverage_level' => 'village',
            'status' => 'active',
            'validated_by' => $admin->id,
            'validated_at' => now(),
            'total_verifications' => 8,
            'total_followups' => 2,
            'total_collected_via_referral' => 80000.00,
            'total_donors_via_referral' => 2,
        ]);

        // ── 4. Create Demo Beneficiary ──
        $benUser = User::create([
            'name' => 'Rahima Begum',
            'name_bn' => 'রহিমা বেগম',
            'email' => 'beneficiary@czm.bd',
            'mobile' => '01700000007',
            'password' => bcrypt('password'),
            'user_type' => 'beneficiary',
            'branch_id' => $dhaka->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $benUser->assignRole('beneficiary');

        $beneficiary = Beneficiary::create([
            'user_id' => $benUser->id,
            'application_no' => 'BEN-2026-000002',
            'primary_person_name' => 'Rahima Begum',
            'primary_person_name_bn' => 'রহিমা বেগম',
            'gender' => 'female',
            'dob' => '1988-06-15',
            'identity_type' => 'nid',
            'identity_no' => '8923481239',
            'mobile' => '01700000007',
            'monthly_income' => 4500.00,
            'mobile_banking_provider' => 'bKash',
            'mobile_banking_account' => '01700000007',
            'category_specific_data_json' => ['family_members' => 3, 'daily_income' => 150],
            'total_assets_value' => 2000.00,
            'total_liabilities' => 5000.00,
            'zakat_category' => 'faqir',
            'status' => 'approved',
            'branch_id' => $dhaka->id,
        ]);

        BeneficiaryHousehold::create([
            'beneficiary_id' => $beneficiary->id,
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Gulshan',
            'address' => 'Kailash Slum, Gulshan',
        ]);

        // Create Case
        $case = CaseRecord::create([
            'case_no' => 'CASE-2026-000002',
            'beneficiary_id' => $beneficiary->id,
            'branch_id' => $dhaka->id,
            'case_type' => 'livelihood',
            'priority' => 'high',
            'stage' => 'disbursement',
            'source' => 'online',
            'requested_amount' => 25000.00,
            'approved_amount' => 20000.00,
            'description' => 'Sadaqah/Zakat livelihood support application for stitching machine.',
        ]);

        // Create Distribution & Disbursement
        $zakatFund = Fund::where('code', 'ZAKAT')->first();
        $distribution = Distribution::create([
            'beneficiary_id' => $beneficiary->id,
            'case_id' => $case->id,
            'fund_id' => $zakatFund->id,
            'category_code' => 'faqir',
            'approved_amount' => 20000.00,
            'distribution_type' => 'cash',
            'status' => 'approved',
        ]);

        Disbursement::create([
            'distribution_id' => $distribution->id,
            'payout_ref' => 'PAY-BK-892348',
            'amount' => 20000.00,
            'payout_channel' => 'bKash',
            'provider_status' => 'settled',
            'acknowledged_at' => now(),
        ]);
    }
}
