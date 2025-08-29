<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PatientHistoryController extends Controller
{
    public function index(Request $request, $patientId): View|string
    {
        $patient = Patient::findOrFail($patientId);
        $historys = $patient->histories()->latest()->get();//->get();
        if ($request->ajax()) {
            return view('patients.history.list', compact('patient', 'historys'))->render();
        }
        return view('patients.history.index', compact('patient', 'historys'));
    }

    public function create($patientId): View
    {
        $patient = Patient::findOrFail($patientId);
        return view('patients.history.create', [
            'patient' => $patient,
            'history' => null,
        ]);
    }

    public function store(Request $request, $patientId): JsonResponse
    {
        $patient = Patient::findOrFail($patientId);
        $data = $request->validate([
            'history_notes' => 'nullable|string',
        ]);

        $patient->histories()->create([
            ...$data,
        ]);

        return response()->json([
            'redirect' => guard_route('patients.history.index', $patient->id),
            'message' => 'History Notes added successfully',
        ]);
    }

    public function edit($patientId, $historyId): View
    {
        $patient = Patient::findOrFail($patientId);
        $history = PatientHistory::findOrFail($historyId);
        return view('patients.history.edit', compact('patient', 'history'));
    }

    public function update(Request $request, $patientId, $historyId): JsonResponse
    {
        $patient = Patient::findOrFail($patientId);
        $history = PatientHistory::findOrFail($historyId);
        $data = $request->validate([
            'history_notes' => 'nullable|string',
        ]);

        $history->update([
            ...$data
        ]);

        return response()->json([
            'redirect' => guard_route('patients.history.index', $patient->id),
            'message' => 'Note updated successfully',
        ]);
    }

    public function destroy($patientId, $noteId): RedirectResponse
    {
        PatientHistory::destroy($noteId);
    
        return redirect()->guard_route('patients.history.index', $patientId)
                        ->with('success','Note deleted successfully');
    }
}
