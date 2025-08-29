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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class FeeNoteController extends Controller
{
    use DropdownTrait;

    public function index($patientId)
    {
        $patient = Patient::findOrFail($patientId); 
        $consultants = Consultant::all(); 
        $chargecodes = ChargeCode::all();
        $feeNotes = $patient->FeeNoteList()->latest()->get();
        $narrative = $this->getDropdownOptions('NARRATIVE');
        $clinics = Clinic::orderBy('name')->get();

        if (request()->ajax()) {
            return view('patients.dashboard.fee_notes.index', compact('consultants', 'chargecodes', 'patient', 'feeNotes', 'narrative', 'clinics'));
        }
        return view('patients.dashboard.fee_notes.index', compact('consultants', 'chargecodes', 'patient', 'feeNotes', 'narrative', 'clinics'));
    }

    public function create($patientId)
    {
        $patient = Patient::findOrFail($patientId); 
        $consultants = Consultant::all(); 
        $chargecodes = ChargeCode::all();
        $narrative = $this->getDropdownOptions('NARRATIVE');
        $clinics = Clinic::orderBy('name')->get();
        return view('patients.dashboard.fee_notes.create', compact('consultants', 'chargecodes', 'patient', 'narrative', 'clinics'));
    }

    public function store(Request $request, $patientId): JsonResponse
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
        $data['patient_id'] = $patientId;

        FeeNote::updateOrCreate(
            ['id' => $request->id],
            $data + $request->only(['comment', 'narrative', 'admission_date', 'discharge_date'])
        );

        $feeNotes = FeeNote::where('patient_id', $request->patient_id)->latest()->get();
        $patient = Patient::find($request->patient_id);

        return response()->json([
            'redirect' => guard_route('fee-notes.index', ['patient' => $patient]),
            'message' => 'feeNotes created successfully',
        ]);
    }

    public function edit($patientId, $feeNoteId)
    {
        $patient = Patient::findOrFail($patientId); 
        $feeNote = FeeNote::findOrFail($feeNoteId);
        $consultants = Consultant::all(); 
        $chargecodes = ChargeCode::all();
        $narrative = $this->getDropdownOptions('NARRATIVE');
        $clinics = Clinic::orderBy('name')->get();
        return view('patients.dashboard.fee_notes.edit', compact('feeNote','consultants', 'chargecodes', 'patient', 'narrative', 'clinics'));
    }

    public function update(Request $request, $patientId, $feeNoteId): JsonResponse
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
        $feeNote = FeeNote::findOrFail($feeNoteId);
        $feeNote = FeeNote::updateOrCreate(
            ['id' => $feeNote->id],
            $data + $request->only(['comment', 'narrative', 'admission_date', 'discharge_date'])
        );
        $patient = Patient::findOrFail($patientId); 
        return response()->json([
            'redirect' => guard_route('fee-notes.index', ['patient' => $patient]),
            'message' => 'feeNotes updated successfully',
        ]);
    }

    public function destroy($patientId,$feeNoteId): RedirectResponse
    {
        $feeNote = FeeNote::findOrFail($feeNoteId);
        $feeNote->delete();

        return redirect()
            ->route('fee-notes.index', ['patient' => $patientId])
            ->with('success', 'Fee Note deleted successfully.');
    } 
}
