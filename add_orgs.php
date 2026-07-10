<?php

use App\Models\Organization;

$orgs = [
    [
        'name_en' => 'Al-Hikmah Foundation',
        'name_bn' => 'আল-হিকমাহ ফাউন্ডেশন',
        'type' => 'national',
        'registration_no' => 'NGO-2023-001',
        'contact_person_name' => 'Abdur Rahman',
        'contact_mobile' => '01711000001',
        'district' => 'Dhaka',
        'total_collected_via_referral' => 500000,
        'total_donors_via_referral' => 45,
    ],
    [
        'name_en' => 'Ibn Sina Trust Welfare',
        'name_bn' => 'ইবনে সিনা ট্রাস্ট ওয়েলফেয়ার',
        'type' => 'national',
        'registration_no' => 'TR-2023-002',
        'contact_person_name' => 'Dr. Kamal Hossain',
        'contact_mobile' => '01711000002',
        'district' => 'Chittagong',
        'total_collected_via_referral' => 850000,
        'total_donors_via_referral' => 82,
    ],
    [
        'name_en' => 'Sylhet Zakat Board',
        'name_bn' => 'সিলেট যাকাত বোর্ড',
        'type' => 'regional',
        'registration_no' => 'CO-2023-003',
        'contact_person_name' => 'Mufti Atiqur Rahman',
        'contact_mobile' => '01711000003',
        'district' => 'Sylhet',
        'total_collected_via_referral' => 300000,
        'total_donors_via_referral' => 20,
    ],
    [
        'name_en' => 'An-Noor Foundation',
        'name_bn' => 'আন-নূর ফাউন্ডেশন',
        'type' => 'district',
        'registration_no' => 'NGO-2023-004',
        'contact_person_name' => 'Tariq Jamil',
        'contact_mobile' => '01711000004',
        'district' => 'Rajshahi',
        'total_collected_via_referral' => 150000,
        'total_donors_via_referral' => 12,
    ],
    [
        'name_en' => 'Khulna Islamic Welfare',
        'name_bn' => 'খুলনা ইসলামিক ওয়েলফেয়ার',
        'type' => 'local_welfare',
        'registration_no' => 'WS-2023-005',
        'contact_person_name' => 'Hasan Mahmud',
        'contact_mobile' => '01711000005',
        'district' => 'Khulna',
        'total_collected_via_referral' => 620000,
        'total_donors_via_referral' => 58,
    ],
    [
        'name_en' => 'Barisal Orphanage Trust',
        'name_bn' => 'বরিশাল এতিমখানা ট্রাস্ট',
        'type' => 'local_welfare',
        'registration_no' => 'TR-2023-006',
        'contact_person_name' => 'Mawlana Sayeed',
        'contact_mobile' => '01711000006',
        'district' => 'Barisal',
        'total_collected_via_referral' => 410000,
        'total_donors_via_referral' => 33,
    ],
];

foreach ($orgs as $orgData) {
    // Generate codes
    $orgData['org_code'] = Organization::generateCode();
    $orgData['referral_code'] = Organization::generateReferralCode();
    $orgData['status'] = 'verified';
    $orgData['verified_at'] = now();
    
    Organization::create($orgData);
    echo "Created: {$orgData['name_en']}\n";
}

echo "Successfully added 6 verified organizations!\n";
