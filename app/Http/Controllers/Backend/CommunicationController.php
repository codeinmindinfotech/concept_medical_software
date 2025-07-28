<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;

class CommunicationController extends Controller
{
    public function index(Patient $patient)
    {
        $communications = $patient->communication() 
            ->where('received', false)
            ->latest()
            ->get();

        if (request()->ajax()) {
            return view('patients.dashboard.communications.index', compact('patient', 'communications'));
        }
        return view('patients.dashboard.communications.index', compact('patient', 'communications'));
    }
    public function markAsReceived(Communication $communication): JsonResponse
    {
        $communication->update(['received' => true]);

        return response()->json(['success' => true]);
    }

}
