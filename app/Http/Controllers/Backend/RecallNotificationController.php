<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recall;
use App\Models\Patient; // Assuming you have a Patient model
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class RecallNotificationController extends Controller
{
    public function index(Request $request): View|string
    {
        $query = Recall::companyOnly()->with('patient','status')->latest();
        
        if (has_role('patient')) {
            $user = auth()->user();
            $query->where('patient_id', $user->id);
        }
        $defaulting = !$request->filled('from') && !$request->filled('to') && !$request->filled('recall_filter');

        if ($defaulting) {
            $request->merge([
                'recall_filter' => 'month',
                'from' => Carbon::now()->startOfMonth()->toDateString(),
                'to' => Carbon::now()->endOfMonth()->toDateString(),
            ]);
        }

        if ($request->filled('first_name')) {
            $query->whereHas('patient', fn ($q) =>
                $q->where('first_name', 'like', '%' . $request->first_name . '%')
            );
        }

        if ($request->filled('surname')) {
            $query->whereHas('patient', fn ($q) =>
                $q->where('surname', 'like', '%' . $request->surname . '%')
            );
        }

        if ($request->filled('from')) {
            $query->whereDate('recall_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('recall_date', '<=', $request->to);
        }
        //->withQueryString()
        $recalls = $query->get();
        if ($request->ajax()) {
            return view('patients.dashboard.recalls.notifications', compact('recalls'))->render();
        }

        return view('patients.dashboard.recalls.notifications', compact('recalls'));
    }

    public function sendEmail($id)
    {
        $recall = Recall::findOrFail($id);

        // TODO: Implement your email sending logic here
        // For example, dispatch a Mailable or queue a job

        return redirect()->back()->with('success', 'Email sent successfully to patient #' . $recall->patient_id);
    }

    public function sendSms($id)
    {
        $recall = Recall::findOrFail($id);

        // TODO: Implement your SMS sending logic here
        // For example, integrate with Twilio or another SMS service

        return redirect()->back()->with('success', 'SMS sent successfully to patient #' . $recall->patient_id);
    }
}

