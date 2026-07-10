<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ZakatCalculatorController;

// ── Public Homepage ──
Route::get('/', function () {
    $organizations = \App\Models\Organization::where('status', 'verified')
        ->orderBy('total_collected_via_referral', 'desc')
        ->take(4)->get();
        
    $volunteers = \App\Models\Volunteer::whereIn('status', ['active', 'verified'])
        ->orderBy('total_collected_via_referral', 'desc')
        ->take(4)->get();

    $totalDonors = \App\Models\Donor::count();
    $totalBeneficiaries = \App\Models\Beneficiary::count();
    $zakatDistributed = \App\Models\Collection::where('payment_status', 'paid')->sum('amount') ?? 0;
    $partnerOrgs = \App\Models\Organization::where('status', 'verified')->count();

    return view('welcome', compact('organizations', 'volunteers', 'totalDonors', 'totalBeneficiaries', 'zakatDistributed', 'partnerOrgs'));
})->name('home');

// ── Beneficiary / Aid Application ──
Route::get('/apply', [\App\Http\Controllers\PublicBeneficiaryController::class, 'create'])->name('public.beneficiary.register');
Route::post('/apply', [\App\Http\Controllers\PublicBeneficiaryController::class, 'store'])->name('public.beneficiary.store');
Route::get('/apply/success', [\App\Http\Controllers\PublicBeneficiaryController::class, 'success'])->name('public.beneficiary.success');

// ── Application Tracking (OTP & Code) ──
Route::get('/track', [\App\Http\Controllers\PublicBeneficiaryController::class, 'track'])->name('public.track');
Route::post('/track/code', [\App\Http\Controllers\PublicBeneficiaryController::class, 'trackByCode'])->name('public.track.code');
Route::post('/track/otp/request', [\App\Http\Controllers\PublicBeneficiaryController::class, 'requestOtp'])->name('public.otp.request');
Route::post('/track/otp/verify', [\App\Http\Controllers\PublicBeneficiaryController::class, 'verifyOtp'])->name('public.otp.verify');
Route::get('/track/mobile', [\App\Http\Controllers\PublicBeneficiaryController::class, 'trackByMobile'])->name('public.track.mobile');

// ── Donor Portal ──
Route::get('/donor/register', [\App\Http\Controllers\PublicDonorController::class, 'create'])->name('donor.register');
Route::post('/donor/register', [\App\Http\Controllers\PublicDonorController::class, 'store'])->name('donor.register.store');
Route::get('/donor/dashboard', [\App\Http\Controllers\PublicDonorController::class, 'dashboard'])
    ->middleware('auth')
    ->name('donor.dashboard');

// ── Volunteer Portal ──
Route::get('/volunteer/register', [\App\Http\Controllers\PublicVolunteerController::class, 'create'])->name('volunteer.register');
Route::post('/volunteer/register', [\App\Http\Controllers\PublicVolunteerController::class, 'store'])->name('volunteer.register.store');
Route::get('/volunteer/dashboard', [\App\Http\Controllers\PublicVolunteerController::class, 'dashboard'])
    ->middleware('auth')
    ->name('volunteer.dashboard');

// ── Organization Portal ──
Route::get('/organization/register', [\App\Http\Controllers\PublicOrganizationController::class, 'create'])->name('organization.register');
Route::post('/organization/register', [\App\Http\Controllers\PublicOrganizationController::class, 'store'])->name('organization.register.store');
Route::get('/organization/dashboard', [\App\Http\Controllers\PublicOrganizationController::class, 'dashboard'])
    ->middleware('auth')
    ->name('organization.dashboard');

// ── Login redirect ──
Route::get('/register', function () {
    return redirect()->route('donor.register');
})->name('register');

// ── Payment & Referral Portal ──
Route::get('/pay', [\App\Http\Controllers\PublicPaymentController::class, 'show'])->name('payment.show');
Route::post('/pay', [\App\Http\Controllers\PublicPaymentController::class, 'process'])->name('payment.process');
Route::get('/pay/bkash/callback', [\App\Http\Controllers\PublicPaymentController::class, 'bkashCallback'])->name('payment.bkash.callback');
Route::get('/pay/success/{receipt}', [\App\Http\Controllers\PublicPaymentController::class, 'success'])->name('payment.success');

Route::get('/r/{code}', [\App\Http\Controllers\PublicReferralController::class, 'orgLink'])->name('referral.org');
Route::get('/v/{code}', [\App\Http\Controllers\PublicReferralController::class, 'volunteerLink'])->name('referral.volunteer');
Route::get('/leaderboard', [\App\Http\Controllers\PublicReferralController::class, 'leaderboard'])->name('leaderboard');
Route::get('/organizations', [\App\Http\Controllers\PublicDirectoryController::class, 'organizations'])->name('public.organizations');
Route::get('/volunteers', [\App\Http\Controllers\PublicDirectoryController::class, 'volunteers'])->name('public.volunteers');

// ── Beneficiary Portal ──
Route::get('/beneficiary/dashboard', [\App\Http\Controllers\PublicBeneficiaryController::class, 'dashboard'])->middleware('auth')->name('beneficiary.dashboard');

// ── Verification Workflow (portal, authenticated) ──
Route::middleware('auth')->group(function () {
    // Volunteer — initial verification (union-scoped)
    Route::get('/volunteer/verifications', [\App\Http\Controllers\VolunteerVerificationController::class, 'index'])->name('volunteer.verifications.index');
    Route::get('/volunteer/verifications/{case}', [\App\Http\Controllers\VolunteerVerificationController::class, 'show'])->name('volunteer.verifications.show');
    Route::post('/volunteer/verifications/{case}/submit', [\App\Http\Controllers\VolunteerVerificationController::class, 'submit'])->name('volunteer.verifications.submit');
    Route::post('/volunteer/verifications/{case}/note', [\App\Http\Controllers\VolunteerVerificationController::class, 'note'])->name('volunteer.verifications.note');

    // Organization admin — final verification (org-scoped)
    Route::get('/organization/verifications', [\App\Http\Controllers\OrgVerificationController::class, 'index'])->name('organization.verifications.index');
    Route::get('/organization/verifications/{verification}', [\App\Http\Controllers\OrgVerificationController::class, 'show'])->name('organization.verifications.show');
    Route::post('/organization/verifications/{verification}/finalize', [\App\Http\Controllers\OrgVerificationController::class, 'finalize'])->name('organization.verifications.finalize');
});

// ── Public Information Pages ──
Route::get('/about', [\App\Http\Controllers\PublicPageController::class, 'about'])->name('public.about');
Route::get('/how-it-works', [\App\Http\Controllers\PublicPageController::class, 'howItWorks'])->name('public.how');
Route::get('/contact', [\App\Http\Controllers\PublicPageController::class, 'contact'])->name('public.contact');
Route::post('/contact', [\App\Http\Controllers\PublicPageController::class, 'contactStore'])->name('public.contact.store');

// ── Public Zakat Calculator (no auth required) ──
Route::get('/zakat-calculator', [\App\Http\Controllers\PublicPageController::class, 'calculator'])->name('public.calculator');

// ── Public Blockchain Transparency Ledger ──
Route::get('/transparency', [\App\Http\Controllers\BlockchainLedgerController::class, 'transparency'])->name('public.transparency');

// ── Geographic Location API (cascading picker: division → district → upazila → union) ──
Route::get('/geo/divisions', [\App\Http\Controllers\GeoController::class, 'divisions'])->name('geo.divisions');
Route::get('/geo/areas/{area}/children', [\App\Http\Controllers\GeoController::class, 'children'])->name('geo.children');

// ── Admin / Staff Authenticated Routes ──
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/impersonate/leave', [\App\Http\Controllers\ImpersonationController::class, 'leave'])->name('impersonate.leave');

    // Donors
    Route::resource('donors', DonorController::class);

    // Beneficiaries
    Route::resource('beneficiaries', BeneficiaryController::class);

    // Cases
    Route::resource('cases', CaseController::class);
    Route::post('cases/{case}/advance', [CaseController::class, 'advanceStage'])->name('cases.advance');

    // Collections
    Route::resource('collections', CollectionController::class);

    // Campaigns
    Route::resource('campaigns', CampaignController::class);

    // Funds
    Route::resource('funds', FundController::class)->only(['index', 'show']);

    // Distributions
    Route::resource('distributions', DistributionController::class);

    // Follow-ups
    Route::resource('followups', \App\Http\Controllers\FollowUpController::class)->only(['index', 'create', 'store']);


    // Branches
    Route::resource('branches', BranchController::class);

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/collections', [ReportController::class, 'collections'])->name('reports.collections');
    Route::get('reports/distributions', [ReportController::class, 'distributions'])->name('reports.distributions');
    Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    
    // Users & Roles
    Route::resource('users', UserController::class)->except(['show']);
    // Route::resource('roles', \App\Http\Controllers\RoleController::class)->except(['show']);

    // Partner Management
    Route::get('admin/organizations', [\App\Http\Controllers\AdminOrganizationController::class, 'index'])->name('admin.organizations.index');
    Route::get('admin/organizations/{organization}', [\App\Http\Controllers\AdminOrganizationController::class, 'show'])->name('admin.organizations.show');
    Route::post('admin/organizations/{organization}/status', [\App\Http\Controllers\AdminOrganizationController::class, 'updateStatus'])->name('admin.organizations.status');
    Route::post('admin/organizations/{organization}/impersonate', [\App\Http\Controllers\AdminOrganizationController::class, 'impersonate'])->name('admin.organizations.impersonate');
    
    Route::get('admin/volunteers', [\App\Http\Controllers\AdminVolunteerController::class, 'index'])->name('admin.volunteers.index');
    Route::get('admin/volunteers/{volunteer}', [\App\Http\Controllers\AdminVolunteerController::class, 'show'])->name('admin.volunteers.show');
    Route::post('admin/volunteers/{volunteer}/status', [\App\Http\Controllers\AdminVolunteerController::class, 'updateStatus'])->name('admin.volunteers.status');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/blockchain/test-send', [SettingController::class, 'blockchainTestSend'])->name('settings.blockchain.test');
    Route::post('settings/blockchain/deploy', [SettingController::class, 'blockchainDeploy'])->name('settings.blockchain.deploy');

    // Audit
    Route::get('audit', [AuditController::class, 'index'])->name('audit.index');

    // Blockchain Ledger (on-chain anchors)
    Route::get('blockchain-ledger', [\App\Http\Controllers\BlockchainLedgerController::class, 'index'])->name('blockchain.ledger');
    Route::post('blockchain-ledger/sync', [\App\Http\Controllers\BlockchainLedgerController::class, 'sync'])->name('blockchain.ledger.sync');

    // Complaints
    Route::resource('complaints', ComplaintController::class);

    // Zakat Calculator
    Route::get('calculator', [ZakatCalculatorController::class, 'index'])->name('calculator.index');
    Route::post('calculator', [ZakatCalculatorController::class, 'calculate'])->name('calculator.calculate');
});
