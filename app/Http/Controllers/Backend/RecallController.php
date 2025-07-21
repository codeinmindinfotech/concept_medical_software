<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Recall;
use App\Traits\DropdownTrait;
use Illuminate\Http\Request;

class RecallController extends Controller
{
    use DropdownTrait;

    public function index(Patient $patient)
    {
        $recalls = $patient->recall()->with('status')->latest()->get();
        $statuses = $this->getDropdownOptions('STATUS');
        if (request()->ajax()) {
            return view('patients.dashboard.recalls.list', compact('recalls', 'statuses', 'patient'));
        }
        return view('patients.dashboard.recalls.list', compact('recalls', 'statuses', 'patient'));
    }

    public function store(Request $request, Patient $patient)
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

        $recalls = Recall::where('patient_id', $request->patient_id)->latest()->get();
        $patient = Patient::find($request->patient_id);

        return response()->json([
            'view' => view('patients.dashboard.recalls.list', compact('recalls', 'patient'))->render()
        ]);
    }

    public function show(Recall $recall)
    {
        return response()->json(['data' => $recall]);
    }

    public function update(Request $request, Patient $patient, Recall $recall)
    {
        $data = $request->validate([
            'patient_id' => 'required',
            'status_id' => 'required',
            'recall_interval' => 'required',
            'note' => 'required',
            'recall_date' => 'required|date',
        ]);

        $id = $request->recall_id ?? '';
        $recall = Recall::updateOrCreate(
            ['id' => $id],
            $data
        );
        return response()->json(['message' => 'Updated']);
    }


    public function destroy($patientId, $recallId)
    {
        $recall = Recall::where('id', $recallId)
            ->where('patient_id', $patientId)
            ->first();

        if (!$recall) {
            return response()->json(['success' => false, 'message' => 'Recall not found or unauthorized.'], 404);
        }

        $recall->delete();

        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
}
