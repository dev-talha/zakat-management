<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function about()
    {
        return view('public.about');
    }

    public function howItWorks()
    {
        return view('public.how-it-works');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function contactStore(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'mobile'  => 'required|string|max:20',
            'subject' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);

        // In production, dispatch a mail job here.
        // For now, just flash a success message.
        return redirect()->route('public.contact')
            ->with('success', 'শুক্রিয়া! আপনার বার্তা পাঠানো হয়েছে। | Thank you! Your message has been received. We will get back to you within 24 hours.');
    }

    public function calculator()
    {
        return view('public.calculator');
    }
}
