<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\WaitingList;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    use DropdownTrait;

    public function index(Patient $patient)
    {
        $clinics = Clinic::orderBy('name')->get();
        extract($this->getCommonDropdowns());
        $waitingLists = $patient->WaitingLists()->latest()->paginate(10);

        if (request()->ajax()) {
            return view('patients.waiting_lists.list', compact('patient', 'clinics', 'categories','waitingLists'));
        }
        return view('patients.waiting_lists.list', compact('patient', 'clinics', 'categories','waitingLists'));
    }

    
    public function store(Request $request, $patientId)
    {
        $data = $request->validate([
            'visit_date' => 'required|date',
            'clinic_id' => 'required|integer',
            'consult_note' => 'required|string',
            'category_id' => 'required|integer',
        ]);
        $data['patient_id'] = $patientId;
        WaitingList::create($data);
    
        return response()->json(['success' => true, 'message' => 'Added']);
    }
    
    public function show($patientId, WaitingList $waitingList)
    {
        return response()->json($waitingList);
    }
 
    public function update(Request $request, $patientId, $id)
    {
      $data = $request->validate([
        'visit_date' => 'required|date',
            'clinic_id' => 'required|integer',
            'consult_note' => 'required|string',
            'category_id' => 'required|integer',
      ]);
    
      $waitingList = WaitingList::where('patient_id', $patientId)->findOrFail($id);
      $waitingList->update($data);
    
      return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }
    

    
    public function destroy($patientId, WaitingList $waitingList)
    {
        $waitingList->delete();
        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
    
}
