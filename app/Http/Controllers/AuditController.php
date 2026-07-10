<?php
namespace App\Http\Controllers;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $logs = Activity::with('causer')->latest()->paginate(50);
        return view('audit.index', compact('logs'));
    }
}
