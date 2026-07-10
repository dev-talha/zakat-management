<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\BeneficiaryHousehold;
use App\Models\CaseRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PublicBeneficiaryController extends Controller
{
    public function create()
    {
        return view('public.beneficiary.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'primary_person_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'zakat_category' => ['required', Rule::in(Beneficiary::zakatCategoryKeys())],
            'assistance_reason' => 'nullable|in:general,medical,food,education,housing,livelihood,emergency',
            'division' => 'required|string',
            'district' => 'required|string',
            'upazila' => 'nullable|string',
            'union' => 'nullable|string',
            'division_id' => 'nullable|exists:geographic_areas,id',
            'district_id' => 'nullable|exists:geographic_areas,id',
            'upazila_id' => 'nullable|exists:geographic_areas,id',
            'union_id' => 'nullable|exists:geographic_areas,id',
            'mobile_banking_provider' => 'required|string',
            'mobile_banking_account' => 'required|string',
        ]);

        // Build category-specific JSON data based on what's submitted
        $categoryData = [];
        if ($request->zakat_category === 'gharimin') {
            $categoryData = [
                'debt_amount' => $request->debt_amount,
                'creditor_name' => $request->creditor_name,
                'reason_for_debt' => $request->reason_for_debt
            ];
        } elseif ($request->zakat_category === 'ibnussabil') {
            $categoryData = [
                'current_location' => $request->current_location,
                'destination' => $request->destination,
                'travel_reason' => $request->travel_reason
            ];
        } elseif (in_array($request->zakat_category, ['faqir', 'miskin'])) {
             $categoryData = [
                'family_members' => $request->family_members,
                'daily_income' => $request->daily_income
            ];
        }

        // Reason for assistance (e.g. medical emergency) is captured separately
        // from the Zakat category.
        $categoryData['assistance_reason'] = $request->assistance_reason ?? 'general';

        // Initially pending. Our AI Verification Service will pick this up (simulated below).
        $beneficiary = Beneficiary::create([
            'application_no' => Beneficiary::generateApplicationNo(),
            'primary_person_name' => $request->primary_person_name,
            'mobile' => $request->mobile,
            'identity_type' => $request->identity_type ?: 'none',
            'identity_no' => $request->identity_no,
            'zakat_category' => $request->zakat_category,
            'mobile_banking_provider' => $request->mobile_banking_provider,
            'mobile_banking_account' => $request->mobile_banking_account,
            'category_specific_data_json' => $categoryData,
            // Most-specific selected area — used for union-scoped volunteer verification.
            'geo_area_id' => $request->union_id ?: $request->upazila_id ?: $request->district_id,
            'status' => 'pending',
            'ai_verification_status' => 'pending' // Will be verified by AI
        ]);

        BeneficiaryHousehold::create([
            'beneficiary_id' => $beneficiary->id,
            'division' => $request->division,
            'district' => $request->district,
            'upazila' => $request->upazila,
            'union_name' => $request->union,
            'address' => $request->address,
        ]);

        // Derive the case type from the reason for assistance (medical, education, …).
        $reasonToCaseType = [
            'medical' => 'medical', 'education' => 'education', 'housing' => 'housing',
            'food' => 'food', 'emergency' => 'emergency', 'livelihood' => 'livelihood',
            'general' => 'general',
        ];
        $caseType = $reasonToCaseType[$request->assistance_reason ?? 'general'] ?? 'general';

        // Auto-create a case for assessment
        CaseRecord::create([
            'case_no' => CaseRecord::generateCaseNo(),
            'beneficiary_id' => $beneficiary->id,
            'case_type' => $caseType,
            'priority' => 'medium',
            'stage' => 'assessment',
            'source' => 'online',
            'requested_amount' => $request->requested_amount ?? 0,
            'description' => "Online Application for Zakat category: " . ucfirst($request->zakat_category),
        ]);

        // Simulate AI Verification Trigger (In production, dispatch a Job)
        // $this->triggerAIVerification($beneficiary);

        return redirect()->route('public.beneficiary.success', ['application_no' => $beneficiary->application_no]);
    }

    public function success(Request $request)
    {
        $applicationNo = $request->query('application_no');
        return view('public.beneficiary.success', compact('applicationNo'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        // Find the beneficiary associated with this user
        $beneficiary = $user->beneficiary;

        if (!$beneficiary) {
            // If user has no beneficiary profile, we look it up by mobile number
            $beneficiary = Beneficiary::where('mobile', $user->mobile)->first();
            if ($beneficiary && !$beneficiary->user_id) {
                // Link them automatically
                $beneficiary->update(['user_id' => $user->id]);
            }
        }

        if ($beneficiary) {
            // All applications under this phone number
            $allApplications = Beneficiary::where('mobile', $beneficiary->mobile)
                ->orderBy('created_at', 'desc')
                ->get();

            // All distributions (approved/disbursed aid)
            $distributions = $beneficiary->distributions()
                ->with(['caseRecord', 'disbursement'])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalAidCount = $distributions->where('status', 'approved')->count();
            $totalAidAmount = $distributions->where('status', 'approved')->sum('approved_amount');
            $latestCase = $beneficiary->cases()->orderBy('created_at', 'desc')->first();
        } else {
            $allApplications = collect();
            $distributions = collect();
            $totalAidCount = 0;
            $totalAidAmount = 0;
            $latestCase = null;
        }

        return view('public.beneficiary.dashboard', compact(
            'user',
            'beneficiary',
            'allApplications',
            'distributions',
            'totalAidCount',
            'totalAidAmount',
            'latestCase'
        ));
    }

    public function track()
    {
        return view('public.beneficiary.track');
    }

    public function trackByCode(Request $request)
    {
        $request->validate([
            'application_no' => 'required|string|max:50',
        ]);

        $applicationNo = trim($request->application_no);
        $beneficiary = Beneficiary::where('application_no', $applicationNo)->first();

        if (!$beneficiary) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => app()->getLocale() === 'bn' 
                        ? 'আবেদন নম্বরটি পাওয়া যায়নি।' 
                        : 'Application number not found.'
                ], 404);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', app()->getLocale() === 'bn' 
                    ? 'আবেদন নম্বরটি পাওয়া যায়নি।' 
                    : 'Application number not found.');
        }

        // Load latest case for timeline
        $latestCase = $beneficiary->cases()->orderBy('created_at', 'desc')->first();
        
        // Structure safe output
        $data = [
            'application_no' => $beneficiary->application_no,
            'primary_person_name' => $this->maskName($beneficiary->primary_person_name),
            'zakat_category' => $beneficiary->zakat_category,
            'status' => $beneficiary->status,
            'created_at' => $beneficiary->created_at->format('d M Y, h:i A'),
            'updated_at' => $beneficiary->updated_at->format('d M Y, h:i A'),
            'stage' => $latestCase ? $latestCase->stage : 'assessment',
            'outcome_status' => $latestCase ? $latestCase->outcome_status : 'pending',
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('public.beneficiary.track', [
            'searchResult' => $data,
            'application_no' => $applicationNo
        ]);
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|max:20',
        ]);

        $mobile = trim($request->mobile);
        
        // Clean mobile number (remove spaces, dashes)
        $mobile = preg_replace('/[^0-9+]/', '', $mobile);

        // Check if any beneficiary exists with this mobile
        $exists = Beneficiary::where('mobile', $mobile)->exists();
        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'bn' 
                    ? 'এই মোবাইল নম্বরটি দিয়ে কোনো আবেদন পাওয়া যায়নি।' 
                    : 'No applications found with this mobile number.'
            ], 404);
        }

        // Generate OTP
        $otp = config('services.sms.dev_mode', true) 
            ? config('services.sms.default_otp', '1234') 
            : str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        // Store OTP in database
        DB::table('beneficiary_otps')->insert([
            'mobile' => $mobile,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send SMS
        $smsService = new \App\Services\SmsService();
        $message = app()->getLocale() === 'bn'
            ? "আপনার সিজেডএম আবেদন ট্র্যাকিং ওটিপি হল {$otp}। এটি ১০ মিনিটের জন্য কার্যকর থাকবে।"
            : "Your CZM application tracking OTP is {$otp}. It will remain valid for 10 minutes.";
            
        $smsService->send($mobile, $message);

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() === 'bn'
                ? 'আপনার মোবাইলে ওটিপি পাঠানো হয়েছে।'
                : 'OTP has been sent to your mobile number.',
            'mobile' => $mobile
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|max:20',
            'otp' => 'required|string|max:10',
        ]);

        $mobile = trim($request->mobile);
        $otp = trim($request->otp);

        // Clean mobile
        $mobile = preg_replace('/[^0-9+]/', '', $mobile);

        // Check active OTP
        $otpRecord = DB::table('beneficiary_otps')
            ->where('mobile', $mobile)
            ->where('otp', $otp)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'bn'
                    ? 'ওটিপি কোডটি সঠিক নয় অথবা মেয়াদ শেষ হয়ে গেছে।'
                    : 'Invalid or expired OTP.'
            ], 422);
        }

        // Mark OTP as used
        DB::table('beneficiary_otps')
            ->where('id', $otpRecord->id)
            ->update(['used_at' => now()]);

        // Store verification in session (30 minutes)
        session([
            'otp_verified_mobile' => $mobile,
            'otp_verified_until' => now()->addMinutes(30)
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('public.track.mobile')
        ]);
    }

    public function trackByMobile(Request $request)
    {
        $mobile = session('otp_verified_mobile');
        $until = session('otp_verified_until');

        if (!$mobile || !$until || now()->isAfter($until)) {
            // Session expired or invalid
            return redirect()->route('public.track')
                ->with('error', app()->getLocale() === 'bn'
                    ? 'আপনার সেশন শেষ হয়ে গেছে। অনুগ্রহ করে আবার মোবাইল নম্বর দিয়ে যাচাই করুন।'
                    : 'Your verification session has expired. Please verify again.');
        }

        // Load all beneficiaries for this mobile
        $beneficiaries = Beneficiary::where('mobile', $mobile)
            ->orderBy('created_at', 'desc')
            ->get();

        $applications = [];
        foreach ($beneficiaries as $beneficiary) {
            $latestCase = $beneficiary->cases()->orderBy('created_at', 'desc')->first();
            $applications[] = [
                'application_no' => $beneficiary->application_no,
                'primary_person_name' => $beneficiary->primary_person_name,
                'zakat_category' => $beneficiary->zakat_category,
                'status' => $beneficiary->status,
                'created_at' => $beneficiary->created_at->format('d M Y'),
                'updated_at' => $beneficiary->updated_at->format('d M Y'),
                'stage' => $latestCase ? $latestCase->stage : 'assessment',
                'outcome_status' => $latestCase ? $latestCase->outcome_status : 'pending',
            ];
        }

        return view('public.beneficiary.track', [
            'mobileResults' => $applications,
            'mobile' => $mobile
        ]);
    }

    private function maskName($name)
    {
        $parts = explode(' ', $name);
        $maskedParts = [];
        foreach ($parts as $part) {
            $len = mb_strlen($part);
            if ($len <= 2) {
                $maskedParts[] = $part;
            } else {
                $maskedParts[] = mb_substr($part, 0, 1) . str_repeat('*', $len - 2) . mb_substr($part, -1);
            }
        }
        return implode(' ', $maskedParts);
    }
}
