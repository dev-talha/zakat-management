<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use App\Models\Fund;
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

/**
 * Adds a larger, realistic batch of Bangladeshi demo data on top of
 * DemoDataSeeder. Safe to re-run (uses firstOrCreate on unique keys).
 */
class BangladeshiDemoSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            'DHK-001' => Branch::firstOrCreate(['code' => 'DHK-001'], ['name' => 'Dhaka Central', 'name_bn' => 'ঢাকা কেন্দ্রীয়', 'region' => 'Dhaka', 'division' => 'Dhaka', 'district' => 'Dhaka', 'status' => 'active']),
            'CTG-001' => Branch::firstOrCreate(['code' => 'CTG-001'], ['name' => 'Chittagong Branch', 'name_bn' => 'চট্টগ্রাম শাখা', 'region' => 'Chittagong', 'division' => 'Chittagong', 'district' => 'Chittagong', 'status' => 'active']),
            'RAJ-001' => Branch::firstOrCreate(['code' => 'RAJ-001'], ['name' => 'Rajshahi Branch', 'name_bn' => 'রাজশাহী শাখা', 'region' => 'Rajshahi', 'division' => 'Rajshahi', 'district' => 'Rajshahi', 'status' => 'active']),
            'SYL-001' => Branch::firstOrCreate(['code' => 'SYL-001'], ['name' => 'Sylhet Branch', 'name_bn' => 'সিলেট শাখা', 'region' => 'Sylhet', 'division' => 'Sylhet', 'district' => 'Sylhet', 'status' => 'active']),
            'KHL-001' => Branch::firstOrCreate(['code' => 'KHL-001'], ['name' => 'Khulna Branch', 'name_bn' => 'খুলনা শাখা', 'region' => 'Khulna', 'division' => 'Khulna', 'district' => 'Khulna', 'status' => 'active']),
        ];

        $admin = User::where('email', 'admin@czm.bd')->first();

        $area = GeographicArea::where('level', 'village')->first()
            ?? GeographicArea::create(['level' => 'village', 'name_bn' => 'ডেমো গ্রাম', 'name_en' => 'Demo Village', 'area_type' => 'rural', 'is_active' => true]);

        $ramadan = Campaign::where('slug', 'ramadan-zakat-2026')->first();
        $education = Campaign::where('slug', 'education-support')->first();
        $zakatFund = Fund::where('code', 'ZAKAT')->first();
        $sadaqahFund = Fund::where('code', 'SADAQAH')->first();

        // ─────────────────────────────────────────────────────────
        // DONORS (individuals + businesses)
        // ─────────────────────────────────────────────────────────
        $donorSeed = [
            ['Mohammad Abdur Rahman', 'মোহাম্মদ আব্দুর রহমান', 'individual', 'Dhaka', 'Dhaka', 'Dhanmondi', 'House 45, Road 8/A, Dhanmondi', 250000, 'DHK-001'],
            ['Ayesha Siddika', 'আয়েশা সিদ্দিকা', 'individual', 'Dhaka', 'Dhaka', 'Mohammadpur', 'Flat 5B, Shyamoli Housing', 60000, 'DHK-001'],
            ['Kazi Nazrul Enterprise', 'কাজী নজরুল এন্টারপ্রাইজ', 'corporate', 'Chattogram', 'Chattogram', 'Agrabad', 'Agrabad C/A, Commercial Plot 21', 500000, 'CTG-001'],
            ['Sharmin Akter', 'শারমিন আক্তার', 'individual', 'Rajshahi', 'Rajshahi', 'Boalia', 'Uposhohor, Sector 3', 40000, 'RAJ-001'],
            ['Habibur Rahman Textiles', 'হাবিবুর রহমান টেক্সটাইলস', 'corporate', 'Dhaka', 'Narayanganj', 'Fatullah', 'BSCIC Industrial Area', 750000, 'DHK-001'],
            ['Farhana Yasmin', 'ফারহানা ইয়াসমিন', 'individual', 'Sylhet', 'Sylhet', 'Zindabazar', 'Ambarkhana Point, Zindabazar', 85000, 'SYL-001'],
            ['Md. Jahangir Alam', 'মোঃ জাহাঙ্গীর আলম', 'individual', 'Khulna', 'Khulna', 'Sonadanga', 'KDA Avenue, Sonadanga', 120000, 'KHL-001'],
            ['Rupa Trading Company', 'রূপা ট্রেডিং কোম্পানি', 'corporate', 'Dhaka', 'Dhaka', 'Motijheel', 'Dilkusha C/A, 3rd Floor', 300000, 'DHK-001'],
            ['Nusrat Jahan', 'নুসরাত জাহান', 'individual', 'Chattogram', 'Chattogram', 'Panchlaish', 'GEC Circle, Panchlaish', 55000, 'CTG-001'],
            ['Anwar Hossain', 'আনোয়ার হোসেন', 'individual', 'Dhaka', 'Gazipur', 'Tongi', 'Cherag Ali, Tongi', 95000, 'DHK-001'],
        ];

        foreach ($donorSeed as $i => $d) {
            [$name, $nameBn, $type, $division, $district, $upazila, $addr, $amount, $branchCode] = $d;
            $branch = $branches[$branchCode];
            $n = $i + 1;

            $u = User::firstOrCreate(
                ['email' => "donor{$n}@czm.bd"],
                [
                    'name' => $name, 'name_bn' => $nameBn,
                    'mobile' => '017' . str_pad((string) (11000000 + $n), 8, '0', STR_PAD_LEFT),
                    'password' => bcrypt('password'), 'user_type' => 'donor',
                    'branch_id' => $branch->id, 'status' => 'active', 'email_verified_at' => now(),
                ]
            );
            $u->assignRole('donor');

            $donor = Donor::firstOrCreate(
                ['user_id' => $u->id],
                ['donor_type' => $type, 'display_name' => $name, 'legal_name' => $name, 'kyc_status' => 'verified']
            );

            DonorAddress::firstOrCreate(
                ['donor_id' => $donor->id],
                ['division' => $division, 'district' => $district, 'upazila' => $upazila, 'address_line' => $addr]
            );

            // 1-2 donations each
            $gw = ['bkash', 'nagad', 'sslcommerz', 'rocket'][$i % 4];
            Collection::firstOrCreate(
                ['receipt_no' => sprintf('REC-2026-1%04d', $n * 2 - 1)],
                [
                    'donor_id' => $donor->id, 'campaign_id' => optional($ramadan)->id, 'branch_id' => $branch->id,
                    'fund_type' => 'zakat', 'source_channel' => 'online', 'amount' => $amount,
                    'payment_gateway' => $gw, 'gateway_transaction_id' => 'TRX-' . strtoupper($gw) . '-' . (900000 + $n),
                    'payment_status' => 'paid', 'status' => 'validated', 'notes' => 'Zakat donation',
                ]
            );

            if ($i % 2 === 0) {
                Collection::firstOrCreate(
                    ['receipt_no' => sprintf('REC-2026-1%04d', $n * 2)],
                    [
                        'donor_id' => $donor->id, 'campaign_id' => optional($education)->id, 'branch_id' => $branch->id,
                        'fund_type' => 'sadaqah', 'source_channel' => 'online', 'amount' => round($amount * 0.2, 2),
                        'payment_gateway' => $gw, 'gateway_transaction_id' => 'TRX-' . strtoupper($gw) . '-' . (950000 + $n),
                        'payment_status' => 'paid', 'status' => 'validated', 'notes' => 'Education Sadaqah',
                    ]
                );
            }
        }

        // ─────────────────────────────────────────────────────────
        // BENEFICIARIES (varied categories / districts / status)
        // ─────────────────────────────────────────────────────────
        $benSeed = [
            ['Salma Khatun', 'সালমা খাতুন', 'female', '1985-03-12', 3500, 'faqir', 'approved', 'Dhaka', 'Dhaka', 'Mirpur', 'Rupnagar slum, Mirpur-2', 'DHK-001', 4],
            ['Abdul Malek', 'আব্দুল মালেক', 'male', '1970-08-25', 5000, 'miskin', 'approved', 'Dhaka', 'Dhaka', 'Kamrangirchar', 'Char slum, Kamrangirchar', 'DHK-001', 6],
            ['Rekha Rani Das', 'রেখা রানী দাস', 'female', '1979-11-05', 4000, 'gharimin', 'under_review', 'Chattogram', 'Chattogram', 'Bakalia', 'Chandanpura, Bakalia', 'CTG-001', 5],
            ['Nurul Islam', 'নুরুল ইসলাম', 'male', '1962-01-30', 3000, 'faqir', 'approved', 'Rajshahi', 'Rajshahi', 'Rajpara', 'Shippara, Rajpara', 'RAJ-001', 3],
            ['Jorina Begum', 'জরিনা বেগম', 'female', '1990-07-19', 4500, 'ibnussabil', 'pending', 'Sylhet', 'Sylhet', 'Kotwali', 'Taltola, Kotwali', 'SYL-001', 2],
            ['Shahin Mia', 'শাহীন মিয়া', 'male', '1988-05-14', 6000, 'gharimin', 'approved', 'Khulna', 'Khulna', 'Daulatpur', 'Mohsin Road, Daulatpur', 'KHL-001', 5],
            ['Momena Khatun', 'মোমেনা খাতুন', 'female', '1955-12-01', 2500, 'miskin', 'approved', 'Dhaka', 'Dhaka', 'Jatrabari', 'Kajla, Jatrabari', 'DHK-001', 1],
            ['Ripon Chandra Roy', 'রিপন চন্দ্র রায়', 'male', '1983-09-09', 5500, 'faqir', 'under_review', 'Rajshahi', 'Natore', 'Natore Sadar', 'Kanaikhali, Natore', 'RAJ-001', 4],
            ['Ambia Bibi', 'আম্বিয়া বিবি', 'female', '1968-04-22', 3200, 'faqir', 'approved', 'Chattogram', 'Coxs Bazar', 'Ramu', 'Fatekharkul, Ramu', 'CTG-001', 6],
            ['Belal Uddin', 'বেলাল উদ্দিন', 'male', '1975-06-18', 4800, 'muallaf', 'approved', 'Sylhet', 'Moulvibazar', 'Sreemangal', 'Tea Garden area, Sreemangal', 'SYL-001', 5],
            ['Taslima Akter', 'তাসলিমা আক্তার', 'female', '1992-02-27', 4000, 'gharimin', 'pending', 'Dhaka', 'Manikganj', 'Manikganj Sadar', 'Beutha, Manikganj', 'DHK-001', 3],
            ['Sirajul Haque', 'সিরাজুল হক', 'male', '1958-10-11', 2800, 'miskin', 'approved', 'Khulna', 'Jashore', 'Jashore Sadar', 'Bejpara, Jashore', 'KHL-001', 2],
        ];

        foreach ($benSeed as $i => $b) {
            [$name, $nameBn, $gender, $dob, $income, $category, $status, $division, $district, $upazila, $addr, $branchCode, $family] = $b;
            $branch = $branches[$branchCode];
            $n = $i + 1;

            $u = User::firstOrCreate(
                ['email' => "beneficiary{$n}@czm.bd"],
                [
                    'name' => $name, 'name_bn' => $nameBn,
                    'mobile' => '018' . str_pad((string) (11000000 + $n), 8, '0', STR_PAD_LEFT),
                    'password' => bcrypt('password'), 'user_type' => 'beneficiary',
                    'branch_id' => $branch->id, 'status' => 'active', 'email_verified_at' => now(),
                ]
            );
            $u->assignRole('beneficiary');

            $mobile = '018' . str_pad((string) (11000000 + $n), 8, '0', STR_PAD_LEFT);
            $beneficiary = Beneficiary::firstOrCreate(
                ['application_no' => sprintf('BEN-2026-1%05d', $n)],
                [
                    'user_id' => $u->id,
                    'primary_person_name' => $name, 'primary_person_name_bn' => $nameBn,
                    'gender' => $gender, 'dob' => $dob,
                    'identity_type' => 'nid', 'identity_no' => (string) (1990000000000 + $n * 137),
                    'mobile' => $mobile, 'monthly_income' => $income,
                    'mobile_banking_provider' => ['bKash', 'Nagad', 'Rocket'][$i % 3],
                    'mobile_banking_account' => $mobile,
                    'category_specific_data_json' => ['family_members' => $family, 'daily_income' => round($income / 30)],
                    'total_assets_value' => $income * 0.5, 'total_liabilities' => $income * 1.5,
                    'zakat_category' => $category, 'status' => $status, 'branch_id' => $branch->id,
                ]
            );

            BeneficiaryHousehold::firstOrCreate(
                ['beneficiary_id' => $beneficiary->id],
                ['division' => $division, 'district' => $district, 'upazila' => $upazila, 'address' => $addr]
            );

            // For approved beneficiaries: create a case + distribution + disbursement
            if ($status === 'approved') {
                $requested = ($family * 5000) + 5000;
                $approved = round($requested * 0.8);

                $case = CaseRecord::firstOrCreate(
                    ['case_no' => sprintf('CASE-2026-1%05d', $n)],
                    [
                        'beneficiary_id' => $beneficiary->id, 'branch_id' => $branch->id,
                        'case_type' => ['livelihood', 'medical', 'education', 'emergency'][$i % 4],
                        'priority' => ['high', 'medium', 'low'][$i % 3], 'stage' => 'disbursement',
                        'source' => 'field', 'requested_amount' => $requested, 'approved_amount' => $approved,
                        'description' => "Zakat support for {$name} ({$category}).",
                    ]
                );

                $dist = Distribution::firstOrCreate(
                    ['case_id' => $case->id],
                    [
                        'beneficiary_id' => $beneficiary->id, 'fund_id' => $zakatFund->id,
                        'category_code' => $category, 'approved_amount' => $approved,
                        'distribution_type' => 'cash', 'status' => 'disbursed',
                    ]
                );

                Disbursement::firstOrCreate(
                    ['distribution_id' => $dist->id],
                    [
                        'payout_ref' => 'PAY-' . strtoupper(['bkash', 'nagad', 'rocket'][$i % 3]) . '-' . (800000 + $n),
                        'amount' => $approved, 'payout_channel' => ['bKash', 'Nagad', 'Rocket'][$i % 3],
                        'provider_status' => 'settled', 'acknowledged_at' => now()->subDays($i),
                    ]
                );
            }
        }

        // ─────────────────────────────────────────────────────────
        // VOLUNTEERS (extra field workers)
        // ─────────────────────────────────────────────────────────
        $org = Organization::first();
        $volSeed = [
            ['Imran Hossain', 'ইমরান হোসেন', 'Teacher', 'Dhaka, Mirpur', 'DHK-001'],
            ['Sadia Islam', 'সাদিয়া ইসলাম', 'Student', 'Chattogram, Halishahar', 'CTG-001'],
            ['Rafiqul Islam', 'রফিকুল ইসলাম', 'Imam', 'Rajshahi, Boalia', 'RAJ-001'],
        ];
        foreach ($volSeed as $i => $v) {
            [$name, $nameBn, $occ, $addrBn, $branchCode] = $v;
            $branch = $branches[$branchCode];
            $n = $i + 1;

            $u = User::firstOrCreate(
                ['email' => "volunteer{$n}@czm.bd"],
                [
                    'name' => $name, 'name_bn' => $nameBn,
                    'mobile' => '019' . str_pad((string) (11000000 + $n), 8, '0', STR_PAD_LEFT),
                    'password' => bcrypt('password'), 'user_type' => 'volunteer',
                    'branch_id' => $branch->id, 'status' => 'active', 'email_verified_at' => now(),
                ]
            );
            $u->assignRole('volunteer');

            Volunteer::firstOrCreate(
                ['user_id' => $u->id],
                [
                    'volunteer_code' => Volunteer::generateCode(),
                    'referral_code' => Volunteer::generateReferralCode(),
                    'organization_id' => optional($org)->id,
                    'nid_no' => (string) (1970000000000 + $n * 211),
                    'name_en' => $name, 'name_bn' => $nameBn,
                    'mobile' => '019' . str_pad((string) (11000000 + $n), 8, '0', STR_PAD_LEFT),
                    'occupation' => $occ, 'address_bn' => $addrBn,
                    'primary_area_id' => $area->id, 'coverage_level' => 'village',
                    'status' => 'active', 'validated_by' => optional($admin)->id, 'validated_at' => now(),
                    'total_verifications' => rand(2, 15), 'total_followups' => rand(0, 5),
                ]
            );
        }

        $this->command?->info('Bangladeshi demo data seeded: '
            . count($donorSeed) . ' donors, ' . count($benSeed) . ' beneficiaries, '
            . count($volSeed) . ' volunteers.');
    }
}
