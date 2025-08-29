<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\WaitingList;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    use DropdownTrait;

    public function index($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $clinics = Clinic::orderBy('name')->get();
        extract($this->getCommonDropdowns());

        $waitingLists = $patient->waitingLists() 
            ->with(['clinic', 'category'])     
            ->latest()
            ->get();

        if (request()->ajax()) {
            return view('patients.dashboard.waiting_lists.index', compact('patient', 'clinics', 'categories','waitingLists'));
        }
        return view('patients.dashboard.waiting_lists.index', compact('patient', 'clinics', 'categories','waitingLists'));
    }

    public function create($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $clinics = Clinic::orderBy('name')->get();
        extract($this->getCommonDropdowns());

        return view('patients.dashboard.waiting_lists.create', compact('patient', 'clinics', 'categories'));
    }

    
    public function store(Request $request, $patientId) : JsonResponse
    {
        $data = $request->validate([
            'visit_date' => 'required|date',
            'clinic_id' => 'required|integer',
            'consult_note' => 'required|string',
            'category_id' => 'required|integer',
        ]);
        $data['patient_id'] = $patientId;
        WaitingList::create($data);
    
        return response()->json([
            'redirect' => guard_route('waiting-lists.index', ['patient' => $request->patient_id]),
            'message' => 'Waiting List created successfully',
        ]);
    }
 
    public function edit($patientId, $waitingId)
    {
        $waitingList = WaitingList::findOrFail($waitingId);
        $clinics = Clinic::orderBy('name')->get();
        extract($this->getCommonDropdowns());

        return view('patients.dashboard.waiting_lists.edit', compact('waitingList','patient', 'clinics', 'categories'));
    }

    public function update(Request $request, $patientId, $id): JsonResponse
    {
      $data = $request->validate([
        'visit_date' => 'required|date',
        'clinic_id' => 'required|integer',
        'consult_note' => 'required|string',
        'category_id' => 'required|integer',
      ]);
    
      $waitingList = WaitingList::where('patient_id', $patientId)->findOrFail($id);
      $waitingList->update($data);
    
      return response()->json([
        'redirect' => guard_route('waiting-lists.index', ['patient' => $request->patient_id]),
        'message' => 'Waiting List Updated successfully',
    ]);
   }
    
    public function destroy($patientId,WaitingList $waitingList): RedirectResponse
    {
        $waitingList->delete();

        return redirect()
            ->route('waiting-lists.index', ['patient' => $patientId])
            ->with('success', 'WaitingList deleted successfully.');
    }
    
}
