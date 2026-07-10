<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Beneficiary;
use App\Models\BeneficiaryHousehold;
use App\Models\CaseRecord;
use App\Models\CaseNote;
use App\Models\Volunteer;
use App\Models\Organization;
use App\Models\GeographicArea;
use Illuminate\Support\Facades\DB;

/**
 * Makes the union-scoped verification workflow demoable and backfills
 * geo_area_id for pre-existing applications.
 *
 * Alignment:
 *   • org@czm.bd's organization becomes owner (created_by) so the org-admin
 *     resolver works.
 *   • volunteer1 & volunteer2 (@czm.bd) → active, same org, same union.
 *   • 3 applications are created in that union (stage field_verification).
 *   • 1 application is created in a DIFFERENT union to prove isolation.
 */
class VerificationDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Backfill existing beneficiaries' geo_area_id from household strings.
        $this->backfillGeoArea();

        $org = Organization::first();
        $orgUser = User::where('email', 'org@czm.bd')->first();
        if ($org && $orgUser && ! $org->created_by) {
            $org->update(['created_by' => $orgUser->id]);
        }

        // Pick a real union under Dhaka district for the demo, plus a second, different union.
        $dhaka = GeographicArea::where('level', 'district')->where('name_en', 'Dhaka')->first();
        $union = GeographicArea::where('level', 'union')
            ->whereHas('parent', fn ($q) => $q->where('parent_id', optional($dhaka)->id))
            ->orderBy('id')->first()
            ?? GeographicArea::where('level', 'union')->orderBy('id')->first();

        $otherUnion = GeographicArea::where('level', 'union')->where('id', '!=', optional($union)->id)
            ->orderBy('id')->first();

        if (! $union || ! $org) {
            $this->command?->warn('Could not resolve a union or organization; skipping demo alignment.');
            return;
        }

        // Two active volunteers in the SAME union (requirement 5: multiple volunteers).
        foreach (['volunteer1@czm.bd', 'volunteer2@czm.bd'] as $email) {
            $u = User::where('email', $email)->first();
            if (! $u) {
                continue;
            }
            Volunteer::where('user_id', $u->id)->update([
                'status' => 'active',
                'organization_id' => $org->id,
                'primary_area_id' => $union->id,
                'coverage_level' => 'union',
            ]);
        }

        // Applications inside the demo union (visible to volunteer1 & 2).
        $apps = [
            ['Halima Khatun', 'হালিমা খাতুন', 'female', 3200, 'faqir', 18000],
            ['Yunus Ali', 'ইউনুস আলী', 'male', 4200, 'miskin', 25000],
            ['Rokeya Begum', 'রোকেয়া বেগম', 'female', 2800, 'gharimin', 30000],
        ];
        foreach ($apps as $i => $a) {
            $this->makeApplication($union, "VRF-2026-1{$i}", $a);
        }

        // One application in a DIFFERENT union (must be invisible to volunteer1/2).
        if ($otherUnion) {
            $this->makeApplication($otherUnion, 'VRF-2026-9', ['Other Union Applicant', 'অন্য ইউনিয়ন', 'male', 3000, 'faqir', 15000]);
        }

        $this->command?->info("Verification demo ready: union #{$union->id} ({$union->name_en}), 2 active volunteers, 3 in-union + 1 other-union applications.");
    }

    private function makeApplication(GeographicArea $union, string $suffix, array $a): void
    {
        [$name, $nameBn, $gender, $income, $category, $requested] = $a;

        $ben = Beneficiary::firstOrCreate(
            ['application_no' => "BEN-2026-{$suffix}"],
            [
                'primary_person_name' => $name, 'primary_person_name_bn' => $nameBn,
                'gender' => $gender, 'mobile' => '0171' . rand(1000000, 9999999),
                'identity_type' => 'nid', 'monthly_income' => $income,
                'zakat_category' => $category, 'status' => 'pending',
                'mobile_banking_provider' => 'bKash',
                'geo_area_id' => $union->id,
            ]
        );

        BeneficiaryHousehold::firstOrCreate(
            ['beneficiary_id' => $ben->id],
            [
                'division' => 'Dhaka', 'district' => 'Dhaka',
                'union_name' => $union->name_en, 'address' => "Village road, {$union->name_en}",
            ]
        );

        CaseRecord::firstOrCreate(
            ['case_no' => "CASE-2026-{$suffix}"],
            [
                'beneficiary_id' => $ben->id, 'case_type' => 'livelihood',
                'priority' => 'medium', 'stage' => 'field_verification', 'source' => 'online',
                'requested_amount' => $requested,
                'description' => "Zakat application awaiting union verification ({$category}).",
            ]
        );
    }

    /** Best-effort backfill: union_name → union, else upazila, else district. */
    private function backfillGeoArea(): void
    {
        $rows = DB::table('beneficiaries')
            ->join('beneficiary_households', 'beneficiary_households.beneficiary_id', '=', 'beneficiaries.id')
            ->whereNull('beneficiaries.geo_area_id')
            ->select('beneficiaries.id', 'beneficiary_households.division', 'beneficiary_households.district', 'beneficiary_households.upazila', 'beneficiary_households.union_name')
            ->get();

        foreach ($rows as $r) {
            $areaId = null;
            if ($r->union_name) {
                $areaId = GeographicArea::where('level', 'union')->where('name_en', $r->union_name)->value('id');
            }
            if (! $areaId && $r->upazila) {
                $areaId = GeographicArea::where('level', 'upazila')->where('name_en', $r->upazila)->value('id');
            }
            if (! $areaId && $r->district) {
                $areaId = GeographicArea::where('level', 'district')->where('name_en', $r->district)->value('id');
            }
            if ($areaId) {
                DB::table('beneficiaries')->where('id', $r->id)->update(['geo_area_id' => $areaId]);
            }
        }
    }
}
