<?php

namespace Database\Seeders;

use App\Models\ZakatCategory;
use App\Models\ZakatCategoryForm;
use App\Models\ZakatCategoryDocument;
use Illuminate\Database\Seeder;

class ZakatCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'code'            => 'fuqara',
                'arabic_name'     => 'الفقراء',
                'name_bn'         => 'ফকির (সম্পূর্ণ নিঃস্ব)',
                'name_en'         => 'Al-Fuqara (The Poor)',
                'description_bn'  => 'যাদের জীবন ধারণের জন্য কোনো সম্পদ বা উপার্জন নেই বা নিসাবের অর্ধেকের কম সম্পদ রয়েছে।',
                'eligibility_criteria_bn' => 'মাসিক আয় নেই বা ন্যূনতম জীবনযাপনের জন্য অপ্রতুল। স্থায়ী বাসস্থান নেই বা নিজের সম্পদ নেই।',
                'icon_class'      => 'fas fa-hand-holding-heart',
                'color_hex'       => '#EF4444',
                'sort_order'      => 1,
                'requires_field_visit' => true,
                'fields' => [
                    ['field_key' => 'income_source',     'label_bn' => 'আয়ের উৎস (যদি থাকে)', 'field_type' => 'select', 'is_required' => false,
                     'field_options' => [['value'=>'none','label_bn'=>'নেই'],['value'=>'day_labor','label_bn'=>'দিনমজুর'],['value'=>'begging','label_bn'=>'ভিক্ষা'],['value'=>'other','label_bn'=>'অন্যান্য']]],
                    ['field_key' => 'monthly_income',    'label_bn' => 'মাসিক আয় (টাকা)', 'field_type' => 'decimal', 'is_required' => true, 'validation_rules' => 'required|numeric|min:0'],
                    ['field_key' => 'has_any_asset',     'label_bn' => 'কোনো সম্পদ আছে কিনা', 'field_type' => 'radio', 'is_required' => true,
                     'field_options' => [['value'=>'no','label_bn'=>'না'],['value'=>'yes','label_bn'=>'হ্যাঁ (বিস্তারিত দিন)']]],
                    ['field_key' => 'asset_details',     'label_bn' => 'সম্পদের বিবরণ (থাকলে)', 'field_type' => 'textarea', 'is_required' => false],
                    ['field_key' => 'housing_ownership', 'label_bn' => 'বাসস্থানের ধরন', 'field_type' => 'select', 'is_required' => true,
                     'field_options' => [['value'=>'homeless','label_bn'=>'গৃহহীন'],['value'=>'rented','label_bn'=>'ভাড়া'],['value'=>'relative','label_bn'=>'আত্মীয়ের কাছে'],['value'=>'govt_shelter','label_bn'=>'সরকারি আশ্রয়']]],
                    ['field_key' => 'local_reference',   'label_bn' => 'স্থানীয় চেয়ারম্যান/মেম্বারের নাম ও মোবাইল', 'field_type' => 'text', 'is_required' => false],
                ],
                'documents' => [
                    ['doc_key' => 'nid_or_birth_cert', 'label_bn' => 'NID বা জন্ম নিবন্ধন সনদ', 'is_required' => true],
                    ['doc_key' => 'chairman_certificate', 'label_bn' => 'ইউপি চেয়ারম্যান/মেম্বারের প্রত্যয়নপত্র', 'is_required' => false],
                ],
            ],
            [
                'code'            => 'masakin',
                'arabic_name'     => 'المساكين',
                'name_bn'         => 'মিসকিন (অভাবগ্রস্ত)',
                'name_en'         => 'Al-Masakin (The Needy)',
                'description_bn'  => 'যাদের আয় আছে কিন্তু মৌলিক চাহিদা মেটানোর জন্য অপ্রতুল।',
                'eligibility_criteria_bn' => 'মাসিক আয় পরিবারের মৌলিক খরচের চেয়ে কম। নিসাবের কম সম্পদ আছে।',
                'icon_class'      => 'fas fa-people-arrows',
                'color_hex'       => '#F97316',
                'sort_order'      => 2,
                'requires_field_visit' => true,
                'fields' => [
                    ['field_key' => 'occupation',         'label_bn' => 'পেশা / কাজের ধরন', 'field_type' => 'text', 'is_required' => true, 'validation_rules' => 'required|string|max:100'],
                    ['field_key' => 'monthly_income',     'label_bn' => 'মাসিক আয় (টাকা)', 'field_type' => 'decimal', 'is_required' => true],
                    ['field_key' => 'monthly_expense',    'label_bn' => 'আনুমানিক মাসিক খরচ (টাকা)', 'field_type' => 'decimal', 'is_required' => true],
                    ['field_key' => 'income_sources',     'label_bn' => 'সকল আয়ের উৎস', 'field_type' => 'textarea', 'is_required' => false],
                    ['field_key' => 'assistance_needed',  'label_bn' => 'কী ধরনের সহায়তা দরকার', 'field_type' => 'select', 'is_required' => true,
                     'field_options' => [['value'=>'food','label_bn'=>'খাদ্য'],['value'=>'medical','label_bn'=>'চিকিৎসা'],['value'=>'education','label_bn'=>'শিক্ষা'],['value'=>'general','label_bn'=>'সাধারণ']]],
                ],
                'documents' => [
                    ['doc_key' => 'nid_or_birth_cert',    'label_bn' => 'NID বা জন্ম নিবন্ধন', 'is_required' => true],
                    ['doc_key' => 'income_proof',         'label_bn' => 'আয়ের প্রমাণপত্র (বেতন স্লিপ / নিয়োগকর্তার পত্র)', 'is_required' => false],
                ],
            ],
            [
                'code'            => 'muallafa',
                'arabic_name'     => 'المؤلفة قلوبهم',
                'name_bn'         => 'মন আকর্ষণ (নওমুসলিম)',
                'name_en'         => "Al-Mu'allafatu Qulubuhum",
                'description_bn'  => 'নতুন মুসলিম যাদের ঈমানী দৃঢ়তা ও পুনর্বাসনে সহায়তা প্রয়োজন।',
                'eligibility_criteria_bn' => 'ইসলাম গ্রহণের প্রমাণ থাকতে হবে। ইমাম বা স্থানীয় আলেমের সুপারিশপত্র প্রয়োজন।',
                'icon_class'      => 'fas fa-heart',
                'color_hex'       => '#8B5CF6',
                'sort_order'      => 3,
                'requires_shariah_review' => true,
                'fields' => [
                    ['field_key' => 'conversion_date',   'label_bn' => 'ইসলাম গ্রহণের তারিখ', 'field_type' => 'date', 'is_required' => true],
                    ['field_key' => 'imam_name',         'label_bn' => 'সুপারিশকারী ইমাম/আলেমের নাম', 'field_type' => 'text', 'is_required' => true],
                    ['field_key' => 'imam_mobile',       'label_bn' => 'ইমাম/আলেমের মোবাইল', 'field_type' => 'phone', 'is_required' => true],
                    ['field_key' => 'mosque_name',       'label_bn' => 'সংশ্লিষ্ট মসজিদের নাম', 'field_type' => 'text', 'is_required' => false],
                    ['field_key' => 'current_needs',     'label_bn' => 'বর্তমান প্রয়োজনের বিবরণ', 'field_type' => 'textarea', 'is_required' => true],
                ],
                'documents' => [
                    ['doc_key' => 'nid_or_birth_cert',   'label_bn' => 'NID বা জন্ম নিবন্ধন', 'is_required' => true],
                    ['doc_key' => 'imam_recommendation', 'label_bn' => 'ইমামের সুপারিশপত্র', 'is_required' => true],
                ],
            ],
            [
                'code'            => 'gharimin',
                'arabic_name'     => 'الغارمين',
                'name_bn'         => 'ঋণগ্রস্ত (আল-গারিমীন)',
                'name_en'         => 'Al-Gharimin (The Indebted)',
                'description_bn'  => 'যারা প্রয়োজনীয় কারণে ঋণগ্রস্ত হয়েছেন এবং নিজে পরিশোধে অক্ষম।',
                'eligibility_criteria_bn' => 'ঋণ নিজের বা পরিবারের বৈধ প্রয়োজনে নেওয়া হয়েছে। ব্যবসায় লোকসান বা দুর্যোগজনিত ঋণ। ঋণের পরিমাণ সম্পদের চেয়ে বেশি।',
                'icon_class'      => 'fas fa-file-invoice-dollar',
                'color_hex'       => '#DC2626',
                'sort_order'      => 4,
                'requires_field_visit' => true,
                'fields' => [
                    ['field_key' => 'total_debt',          'label_bn' => 'মোট ঋণের পরিমাণ (টাকা)', 'field_type' => 'decimal', 'is_required' => true, 'validation_rules' => 'required|numeric|min:1'],
                    ['field_key' => 'debt_reason',         'label_bn' => 'ঋণ নেওয়ার কারণ', 'field_type' => 'select', 'is_required' => true,
                     'field_options' => [['value'=>'medical','label_bn'=>'চিকিৎসা'],['value'=>'business_loss','label_bn'=>'ব্যবসায় লোকসান'],['value'=>'disaster','label_bn'=>'দুর্যোগ/বন্যা'],['value'=>'food','label_bn'=>'খাদ্য সংকট'],['value'=>'education','label_bn'=>'সন্তানের শিক্ষা'],['value'=>'other','label_bn'=>'অন্যান্য']]],
                    ['field_key' => 'creditor_name',       'label_bn' => 'ঋণদাতার নাম', 'field_type' => 'text', 'is_required' => true],
                    ['field_key' => 'creditor_mobile',     'label_bn' => 'ঋণদাতার মোবাইল', 'field_type' => 'phone', 'is_required' => false],
                    ['field_key' => 'debt_due_date',       'label_bn' => 'ঋণ পরিশোধের শেষ তারিখ', 'field_type' => 'date', 'is_required' => false],
                    ['field_key' => 'repayment_ability',   'label_bn' => 'নিজে পরিশোধের সক্ষমতা আছে কিনা', 'field_type' => 'radio', 'is_required' => true,
                     'field_options' => [['value'=>'no','label_bn'=>'না, সম্পূর্ণ অক্ষম'],['value'=>'partial','label_bn'=>'আংশিক সক্ষম']]],
                ],
                'documents' => [
                    ['doc_key' => 'nid_or_birth_cert',    'label_bn' => 'NID বা জন্ম নিবন্ধন', 'is_required' => true],
                    ['doc_key' => 'debt_document',        'label_bn' => 'ঋণের চুক্তিপত্র / দলিল / স্বীকারোক্তি', 'is_required' => true],
                    ['doc_key' => 'creditor_statement',   'label_bn' => 'ঋণদাতার বিবৃতি (যদি পাওয়া যায়)', 'is_required' => false],
                ],
            ],
            [
                'code'            => 'fi_sabilillah',
                'arabic_name'     => 'في سبيل الله',
                'name_bn'         => 'আল্লাহর রাস্তায় (ফি সাবিলিল্লাহ)',
                'name_en'         => 'Fi-Sabilillah (In the Way of Allah)',
                'description_bn'  => 'ধর্মীয় শিক্ষা, দাওয়াহ বা ইসলামের কল্যাণে নিয়োজিত অভাবগ্রস্ত ব্যক্তি।',
                'eligibility_criteria_bn' => 'মাদ্রাসা ছাত্র যার ভরণপোষণ নেই। দাওয়াহ কাজে নিয়োজিত কিন্তু আয় নেই। শরিয়াহ বোর্ড অনুমোদিত কার্যক্রমে অংশগ্রহণকারী।',
                'icon_class'      => 'fas fa-mosque',
                'color_hex'       => '#059669',
                'sort_order'      => 5,
                'requires_shariah_review' => true,
                'fields' => [
                    ['field_key' => 'institution_name',  'label_bn' => 'মাদ্রাসা/প্রতিষ্ঠানের নাম', 'field_type' => 'text', 'is_required' => true],
                    ['field_key' => 'institution_address','label_bn' => 'প্রতিষ্ঠানের ঠিকানা', 'field_type' => 'text', 'is_required' => true],
                    ['field_key' => 'enrollment_class',  'label_bn' => 'শ্রেণী/বর্ষ', 'field_type' => 'text', 'is_required' => false],
                    ['field_key' => 'guardian_income',   'label_bn' => 'অভিভাবকের মাসিক আয় (টাকা)', 'field_type' => 'decimal', 'is_required' => true],
                    ['field_key' => 'scholarship_received','label_bn' => 'অন্য কোনো বৃত্তি পাচ্ছেন কিনা', 'field_type' => 'radio', 'is_required' => true,
                     'field_options' => [['value'=>'no','label_bn'=>'না'],['value'=>'yes','label_bn'=>'হ্যাঁ (বিস্তারিত)']]],
                ],
                'documents' => [
                    ['doc_key' => 'nid_or_birth_cert',   'label_bn' => 'NID বা জন্ম নিবন্ধন', 'is_required' => true],
                    ['doc_key' => 'enrollment_certificate','label_bn' => 'ভর্তির সনদপত্র', 'is_required' => true],
                    ['doc_key' => 'institution_letter',  'label_bn' => 'প্রতিষ্ঠান প্রধানের সুপারিশপত্র', 'is_required' => false],
                ],
            ],
            [
                'code'            => 'ibn_sabil',
                'arabic_name'     => 'ابن السبيل',
                'name_bn'         => 'মুসাফির/বিপদগ্রস্ত পথচারী (ইবনুস সাবিল)',
                'name_en'         => 'Ibn-as-Sabil (Traveler in Need)',
                'description_bn'  => 'সফরে এসে অর্থাভাবে বিপদে পড়া ব্যক্তি, যদিও স্বদেশে সচ্ছল।',
                'eligibility_criteria_bn' => 'সফর বৈধ উদ্দেশ্যে (চিকিৎসা, ব্যবসা, শিক্ষা)। সাময়িক অভাবে পড়েছেন। স্বদেশে ফেরার পথ বা চিকিৎসার প্রয়োজন।',
                'icon_class'      => 'fas fa-route',
                'color_hex'       => '#0EA5E9',
                'sort_order'      => 6,
                'requires_field_visit' => false,
                'fields' => [
                    ['field_key' => 'travel_purpose',    'label_bn' => 'সফরের উদ্দেশ্য', 'field_type' => 'select', 'is_required' => true,
                     'field_options' => [['value'=>'medical','label_bn'=>'চিকিৎসা'],['value'=>'education','label_bn'=>'শিক্ষা'],['value'=>'business','label_bn'=>'ব্যবসা'],['value'=>'family','label_bn'=>'পারিবারিক'],['value'=>'disaster_affected','label_bn'=>'দুর্যোগে ক্ষতিগ্রস্ত']]],
                    ['field_key' => 'origin_district',   'label_bn' => 'স্থায়ী বাড়ির জেলা', 'field_type' => 'text', 'is_required' => true],
                    ['field_key' => 'needed_amount',     'label_bn' => 'প্রয়োজনীয় আনুমানিক পরিমাণ (টাকা)', 'field_type' => 'decimal', 'is_required' => true],
                    ['field_key' => 'emergency_contact', 'label_bn' => 'জরুরি যোগাযোগ (পরিবার/আত্মীয়)', 'field_type' => 'phone', 'is_required' => true],
                    ['field_key' => 'situation_details', 'label_bn' => 'বিস্তারিত পরিস্থিতি', 'field_type' => 'textarea', 'is_required' => true],
                ],
                'documents' => [
                    ['doc_key' => 'nid_or_birth_cert',   'label_bn' => 'NID বা পরিচয়পত্র', 'is_required' => true],
                    ['doc_key' => 'travel_proof',        'label_bn' => 'সফরের প্রমাণ (টিকিট, মেডিকেল পেপার ইত্যাদি)', 'is_required' => false],
                ],
            ],
            [
                'code'            => 'riqab',
                'arabic_name'     => 'في الرقاب',
                'name_bn'         => 'মুক্তি (আর-রিকাব)',
                'name_en'         => 'Ar-Riqab (Emancipation)',
                'description_bn'  => 'আধুনিক প্রেক্ষাপটে: দাসত্বের মতো পরিস্থিতি, বন্ড-লেবার বা জোরপূর্বক ঋণ থেকে মুক্তি।',
                'eligibility_criteria_bn' => 'শরিয়াহ বোর্ডের অনুমোদন প্রয়োজন। এই খাতে বিতরণ সর্বদা শরিয়াহ রিভিউ সাপেক্ষ।',
                'icon_class'      => 'fas fa-unlock',
                'color_hex'       => '#7C3AED',
                'sort_order'      => 7,
                'requires_shariah_review' => true,
                'fields' => [
                    ['field_key' => 'situation_description','label_bn' => 'পরিস্থিতির বিস্তারিত বিবরণ', 'field_type' => 'textarea', 'is_required' => true],
                    ['field_key' => 'bond_amount',          'label_bn' => 'বন্ডের পরিমাণ (যদি প্রযোজ্য)', 'field_type' => 'decimal', 'is_required' => false],
                    ['field_key' => 'witness_name',         'label_bn' => 'সাক্ষীর নাম ও মোবাইল', 'field_type' => 'text', 'is_required' => false],
                ],
                'documents' => [
                    ['doc_key' => 'nid_or_birth_cert',   'label_bn' => 'NID বা পরিচয়পত্র', 'is_required' => true],
                    ['doc_key' => 'supporting_evidence', 'label_bn' => 'সহায়ক প্রমাণপত্র', 'is_required' => false],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $fields    = $catData['fields'] ?? [];
            $documents = $catData['documents'] ?? [];
            unset($catData['fields'], $catData['documents']);

            $category = ZakatCategory::updateOrCreate(['code' => $catData['code']], $catData);

            foreach ($fields as $i => $field) {
                ZakatCategoryForm::updateOrCreate(
                    ['zakat_category_id' => $category->id, 'field_key' => $field['field_key']],
                    array_merge($field, ['sort_order' => $i + 1, 'is_active' => true])
                );
            }

            foreach ($documents as $i => $doc) {
                ZakatCategoryDocument::updateOrCreate(
                    ['zakat_category_id' => $category->id, 'doc_key' => $doc['doc_key']],
                    array_merge($doc, ['sort_order' => $i + 1, 'is_active' => true])
                );
            }
        }
    }
}
