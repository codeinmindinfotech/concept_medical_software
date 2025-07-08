<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientPhysical;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PatientPhysicalController extends Controller
{
    public function index(Request $request, Patient $patient): View|string
    {
        $physicals = $patient->physicalNotes()->latest()->paginate(10);
        if ($request->ajax()) {
            return view('patients.physical.list', compact('patient', 'physicals'))->render();
        }
        return view('patients.physical.index', compact('patient', 'physicals'));
    }

    public function create(Patient $patient): View
    {
        return view('patients.physical.create', [
            'patient' => $patient,
            'physical' => null,
        ]);
    }

    public function store(Request $request, Patient $patient): JsonResponse
    {
        $data = $request->validate([
            'physical_notes' => 'nullable|string',
        ]);

        $patient->physicalNotes()->create([
            ...$data,
        ]);

        return response()->json([
            'redirect' => route('patients.physical.index', $patient->id),
            'message' => 'Physical Notes added successfully',
        ]);
    }

    public function edit(Patient $patient, PatientPhysical $physical): View
    {
        return view('patients.physical.edit', compact('patient', 'physical'));
    }

    public function update(Request $request, Patient $patient, PatientPhysical $physical): JsonResponse
    {
        $data = $request->validate([
            'physical_notes' => 'nullable|string',
        ]);

        $physical->update([
            ...$data
        ]);

        return response()->json([
            'redirect' => route('patients.physical.index', $patient->id),
            'message' => 'Note updated successfully',
        ]);
    }

    public function destroy($patientId, $noteId): RedirectResponse
    {
        PatientPhysical::destroy($noteId);
    
        return redirect()->route('patients.physical.index', $patientId)
                        ->with('success','Note deleted successfully');
    }
}
