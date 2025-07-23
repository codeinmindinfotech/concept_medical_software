<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FeeNote;
use App\Models\Patient;
use App\Models\Recall;
use Illuminate\Http\Request;

class PatientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $patient = Patient::findOrFail($request->id); // ✅ FIXED

        // $patient = Patient::where('id', 1)->first();
        $recalls = Recall::where('patient_id', $patient->id)->get();
        $feeNotes = FeeNote::where('patient_id', $patient->id)->get();
    
        $tab = $request->tab ?? 'recalls';
    
        return view('patients.dashboard.index', [
            'patient' => $patient,
            'recalls' => $recalls,
            'feeNotes' => $feeNotes,
            'activeTab' => $tab,
        ]);
    }

    
    public function storeRecall(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'recall_date' => 'required|date',
            'note' => 'nullable|string|max:500',
            'recall_id' => 'nullable|exists:recalls,id',
        ]);

        if ($request->recall_id) {
            $recall = Recall::findOrFail($request->recall_id);
            $recall->update([
                'recall_date' => $request->recall_date,
                'note' => $request->note,
            ]);
        } else {
            $recall = Recall::create([
                'patient_id' => $request->patient_id,
                'recall_date' => $request->recall_date,
                'note' => $request->note,
            ]);
        }

        // Return updated list partial HTML for AJAX refresh
        $recalls = Recall::where('patient_id', $request->patient_id)->get();
        $html = view('patients.dashboard.tabs.recall.list', compact('recalls'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function deleteRecall(Request $request)
    {
        $request->validate(['id' => 'required|exists:recalls,id']);

        $recall = Recall::findOrFail($request->id);
        $patient_id = $recall->patient_id;
        $recall->delete();

        // Return updated list partial HTML for AJAX refresh
        $recalls = Recall::where('patient_id', $patient_id)->get();
        $html = view('patients.dashboard.tabs.recall.list', compact('recalls'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }
}
