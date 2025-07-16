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

    public function index(Request $request)
    {
        $clinics = Clinic::all();
        $consultants = Consultant::all(); // 👈 Add this line
        // $categories = Category::all();
        $chargecodes = ChargeCode::all();
        $narrative = $this->getDropdownOptions('NARRATIVE');
        $feeNotes = FeeNote::with('chargecode')->where('patient_id', $request->patient_id)->get();
        $patient = Patient::find($request->patient_id);
        if ($request->ajax()) {
            return response()->json([
                'view' => view('patients.fee_notes.list', compact('patient','feeNotes','clinics','narrative','consultants','chargecodes'))->render()
            ]);
        }
    
        // if full page needed
        return view('patients.dashboard', compact('feeNotes'));
    }

    public function store(Request $request, Patient $patient)
{
    $data = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'clinic_id' => 'required',
        'consultant_id' => 'required',
        'chargecode_id' => 'required',
        'qty' => 'required|numeric',
        'charge_gross' => 'required|numeric',
        'charge_net' => 'required|numeric',
        'vat_rate_percent' => 'required|numeric',
        'line_total' => 'required|numeric',
        'visit_date' => 'required|date',
        // admission/discharge optional
    ]);
    $data['patient_id'] = $patient->id;

    FeeNote::updateOrCreate(
        ['id' => $request->fee_note_id],
        $data + $request->only(['comment', 'narrative', 'admission_date', 'discharge_date'])
    );

    $feeNotes = FeeNote::where('patient_id', $request->patient_id)->latest()->get();
    $patient = Patient::find($request->patient_id);

    return response()->json([
        'view' => view('patients.dashboard.fee_notes.list', compact('feeNotes','patient'))->render()
    ]);
}

public function show(FeeNote $feeNote)
{
    return response()->json(['data' => $feeNote]);
}

public function update(Request $request, FeeNote $feeNote)
{
    $feeNote->update($request->all());
    return response()->json(['message' => 'Updated']);
}

public function destroy(FeeNote $feeNote)
{
    $feeNote->delete();
    return response()->json(['message' => 'Deleted']);
}

}
