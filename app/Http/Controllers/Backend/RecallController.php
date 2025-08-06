<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Recall;
use App\Traits\DropdownTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecallController extends Controller
{
    use DropdownTrait;

    public function index(Patient $patient)
    {
        $recalls = $patient->recall()->with('status')->latest()->get();
        $statuses = $this->getDropdownOptions('STATUS');
        if (request()->ajax()) {
            return view('patients.dashboard.recalls.index', compact('recalls', 'statuses', 'patient'));
        }
        return view('patients.dashboard.recalls.index', compact('recalls', 'statuses', 'patient'));
    }

    public function create(Patient $patient)
    {
        $patient = Patient::findOrFail($patient->id); 
        $statuses = $this->getDropdownOptions('STATUS');
        return view('patients.dashboard.recalls.create', compact('patient','statuses'));
    }

    public function store(Request $request, Patient $patient): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'status_id' => 'required',
            'recall_interval' => 'required',
            'note' => 'required',
            'recall_date' => 'required|date',
        ]);
        $data['patient_id'] = $patient->id;

        Recall::updateOrCreate(
            ['id' => $request->recall_id],
            $data );

        $recalls = Recall::where('patient_id', $patient->id)->latest()->get();
        $patient = Patient::find($patient->id);

        return response()->json([
            'redirect' => route('recalls.recalls.index', ['patient' => $patient]),
            'message' => 'Recall created successfully',
        ]);
    }

    public function edit(Patient $patient, $recallId)
    {
        $recall = Recall::findOrFail($recallId);
        $patient = Patient::findOrFail($patient->id); 
        $statuses = $this->getDropdownOptions('STATUS');
        return view('patients.dashboard.recalls.edit', compact('patient','recall', 'statuses'));
    }

    public function show(Recall $recall)
    {
        return response()->json(['data' => $recall]);
    }

    public function update(Request $request, Patient $patient, $recallId): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required',
            'status_id' => 'required',
            'recall_interval' => 'required',
            'note' => 'required',
            'recall_date' => 'required|date',
        ]);

        $recall = Recall::findOrFail($recallId);
        $recall->update($request->all());

        return response()->json([
            'redirect' => route('recalls.recalls.index', ['patient' => $patient->id]),
            'message' => 'Recall updated successfully',
        ]);
    }

    public function destroy(Patient $patient,Recall $recall): RedirectResponse
    {
        $recall->delete();

        return redirect()
            ->route('recalls.recalls.index', ['patient' => $patient->id])
            ->with('success', 'Recall deleted successfully.');
    }    
}
