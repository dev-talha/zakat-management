<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Donor;
use App\Models\Organization;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicPaymentController extends Controller
{
    /**
     * Show the Zakat payment page.
     */
    public function show(Request $request)
    {
        $amount    = $request->query('amount', null);
        $refCode   = $request->query('ref', session('referral_code'));
        $refType   = $request->query('rtype', session('referral_type'));
        $refName   = session('referral_name');

        // Resolve referral name from DB if coming from direct URL
        if ($refCode && !$refName) {
            if ($refType === 'org') {
                $org = Organization::where('referral_code', $refCode)->value('name_en');
                $refName = $org;
            } elseif ($refType === 'volunteer') {
                $vol = Volunteer::where('referral_code', $refCode)->value('name_en');
                $refName = $vol;
            }
        }

        return view('public.payment.zakat', compact('amount', 'refCode', 'refType', 'refName'));
    }

    /**
     * Process the Zakat payment (store collection record).
     */
    public function process(Request $request)
    {
        $request->validate([
            'amount'          => 'required|numeric|min:100',
            'payment_gateway' => 'required|in:bkash,nagad,rocket,sslcommerz,bank',
            'payer_name'      => 'required|string|max:255',
            'payer_mobile'    => 'required|string|max:20',
        ]);

        // Get or create donor
        $user  = Auth::user();
        $donor = null;
        if ($user) {
            $donor = Donor::firstOrCreate(
                ['user_id' => $user->id],
                ['donor_type' => 'individual', 'display_name' => $user->name, 'kyc_status' => 'pending']
            );
        }

        // Generate a simulated transaction ID
        $txnId = strtoupper($request->payment_gateway) . '-' . date('YmdHis') . '-' . rand(1000, 9999);

        $isBkash = $request->payment_gateway === 'bkash';

        // Create collection record
        $collection = Collection::create([
            'receipt_no'           => Collection::generateReceiptNo(),
            'donor_id'             => $donor?->id,
            'fund_type'            => $request->fund_type ?? 'zakat',
            'source_channel'       => 'online',
            'amount'               => $request->amount,
            'currency'             => 'BDT',
            'is_anonymous'         => $request->anonymous ? true : false,
            'status'               => $isBkash ? 'pending' : 'completed',
            'notes'                => 'Online payment via ' . $request->payment_gateway,
            'referral_code'        => $request->referral_code,
            'referral_type'        => $request->referral_type,
            'payment_gateway'      => $request->payment_gateway,
            'gateway_transaction_id' => $isBkash ? null : $txnId,
            'payment_status'       => $isBkash ? 'pending' : 'paid',
        ]);

        if ($isBkash) {
            try {
                $bkashService = new \App\Services\BkashService();
                $paymentResult = $bkashService->createPayment($request->amount, $collection->receipt_no);
                
                if (isset($paymentResult['bkashURL'])) {
                    return redirect($paymentResult['bkashURL']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'bKash API error: ' . $e->getMessage());
            }
        }

        // Update referral stats for non-bKash mock payments
        if ($request->referral_code && $request->referral_type) {
            if ($request->referral_type === 'org') {
                Organization::where('referral_code', $request->referral_code)->increment('total_collected_via_referral', $request->amount);
                Organization::where('referral_code', $request->referral_code)->increment('total_donors_via_referral');
            } elseif ($request->referral_type === 'volunteer') {
                Volunteer::where('referral_code', $request->referral_code)->increment('total_collected_via_referral', $request->amount);
                Volunteer::where('referral_code', $request->referral_code)->increment('total_donors_via_referral');
            }
        }

        // Clear referral session
        session()->forget(['referral_code', 'referral_type', 'referral_name']);

        return redirect()->route('payment.success', ['receipt' => $collection->receipt_no])
            ->with('success', 'Zakat payment recorded successfully! জাযাকাল্লাহু খাইরান!');
    }

    /**
     * Handle bKash Callback
     */
    public function bkashCallback(Request $request)
    {
        $paymentID = $request->query('paymentID');
        $status = $request->query('status');

        if ($status === 'success') {
            try {
                $bkashService = new \App\Services\BkashService();
                $result = $bkashService->executePayment($paymentID);

                if (isset($result['statusCode']) && $result['statusCode'] === '0000') {
                    $receiptNo = $result['merchantInvoiceNumber'];
                    $collection = Collection::where('receipt_no', $receiptNo)->first();
                    
                    if ($collection && $collection->payment_status === 'pending') {
                        $collection->update([
                            'status' => 'completed',
                            'payment_status' => 'paid',
                            'gateway_transaction_id' => $result['trxID'] ?? null,
                        ]);

                        // Update referral stats
                        if ($collection->referral_code && $collection->referral_type) {
                            if ($collection->referral_type === 'org') {
                                Organization::where('referral_code', $collection->referral_code)->increment('total_collected_via_referral', $collection->amount);
                                Organization::where('referral_code', $collection->referral_code)->increment('total_donors_via_referral');
                            } elseif ($collection->referral_type === 'volunteer') {
                                Volunteer::where('referral_code', $collection->referral_code)->increment('total_collected_via_referral', $collection->amount);
                                Volunteer::where('referral_code', $collection->referral_code)->increment('total_donors_via_referral');
                            }
                        }

                        // Clear referral session
                        session()->forget(['referral_code', 'referral_type', 'referral_name']);

                        return redirect()->route('payment.success', ['receipt' => $collection->receipt_no])
                            ->with('success', 'bKash payment successful! জাযাকাল্লাহু খাইরান!');
                    }
                }
            } catch (\Exception $e) {
                return redirect()->route('payment.show')->with('error', 'Error verifying bKash payment: ' . $e->getMessage());
            }
        }

        return redirect()->route('payment.show')->with('error', 'Payment cancelled or failed. Please try again.');
    }

    /**
     * Payment success page.
     */
    public function success(Request $request)
    {
        $receiptNo  = $request->route('receipt');
        $collection = Collection::where('receipt_no', $receiptNo)->first();

        return view('public.payment.success', compact('collection'));
    }
}
