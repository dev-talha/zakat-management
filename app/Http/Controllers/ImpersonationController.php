<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function leave()
    {
        if (!session()->has('impersonated_by')) {
            return redirect('/')->with('error', 'Not currently impersonating.');
        }

        $adminId = session()->pull('impersonated_by');
        $admin = User::find($adminId);

        if ($admin) {
            Auth::login($admin);
            return redirect()->route('dashboard')->with('success', 'Restored to admin session.');
        }

        return redirect('/')->with('error', 'Failed to restore admin session.');
    }
}
