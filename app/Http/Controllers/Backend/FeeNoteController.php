<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChargeCode;
use App\Models\Clinic;
use App\Models\Consultant;
use Illuminate\Http\Request;
use App\Models\FeeNote;
use App\Models\Patient;
use App\Traits\DropdownTrait;

class FeeNoteController extends Controller
{
    use DropdownTrait;

    public function index(Patient $patient)
    {
        $consultants = Consultant::all(); 
        $chargecodes = ChargeCode::all();
        $feeNotes = $patient->FeeNoteList()->latest()->get();
        $narrative = $this->getDropdownOptions('NARRATIVE');
        $clinics = Clinic::orderBy('name')->get();
        $feeNotes = $patient->FeeNoteList()->latest()->get();

        if (request()->ajax()) {
            return view('patients.dashboard.fee_notes.list', compact('consultants', 'chargecodes', 'patient', 'feeNotes', 'narrative', 'clinics'));
        }
        return view('patients.dashboard.fee_notes.list', compact('consultants', 'chargecodes', 'patient', 'feeNotes', 'narrative', 'clinics'));
    }

    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'clinic_id' => 'required',
            'consultant_id' => 'required',
            'chargecode_id' => 'required',
            'narrative' => 'required',
            'qty' => 'required|numeric',
            'charge_gross' => 'required|numeric',
            'charge_net' => 'required|numeric',
            'vat_rate_percent' => 'required|numeric',
            'line_total' => 'required|numeric',
            'procedure_date' => 'required|date',
        ]);
        $data['patient_id'] = $patient->id;

        FeeNote::updateOrCreate(
            ['id' => $request->id],
            $data + $request->only(['comment', 'narrative', 'admission_date', 'discharge_date'])
        );

        $feeNotes = FeeNote::where('patient_id', $request->patient_id)->latest()->get();
        $patient = Patient::find($request->patient_id);

        return response()->json([
            'view' => view('patients.dashboard.fee_notes.list', compact('feeNotes', 'patient'))->render()
        ]);
    }

    public function show(FeeNote $feeNote)
    {
        return response()->json(['data' => $feeNote]);
    }

    public function update(Request $request, Patient $patient, FeeNote $feeNote)
    {
        $data = $request->validate([
            'clinic_id' => 'required',
            'consultant_id' => 'required',
            'chargecode_id' => 'required',
            'qty' => 'required|numeric',
            'charge_gross' => 'required|numeric',
            'charge_net' => 'required|numeric',
            'vat_rate_percent' => 'required|numeric',
            'line_total' => 'required|numeric',
            'procedure_date' => 'required|date',
        ]);
        $id = $request->id ?? '';
        $feeNote = FeeNote::updateOrCreate(
            ['id' => $id],
            $data + $request->only(['comment', 'narrative', 'admission_date', 'discharge_date'])
        );
        return response()->json(['message' => 'Updated']);
    }


    public function destroy($patientId, $noteId)
    {
        $feeNote = FeeNote::where('id', $noteId)
            ->where('patient_id', $patientId)
            ->first();

        if (!$feeNote) {
            return response()->json(['success' => false, 'message' => 'Fee note not found or unauthorized.'], 404);
        }

        $feeNote->delete();

        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
}
