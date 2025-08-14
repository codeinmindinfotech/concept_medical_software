<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ChargeCode;
use App\Models\Clinic;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Illuminate\Http\Request;

class PlannerController extends Controller
{
    use DropdownTrait;
    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $diary_status = $this->getDropdownOptions('DIARY_CATEGORIES');
        $procedures = ChargeCode::all();
        $appointments = Appointment::with(['patient', 'appointmentType'])
            ->whereDate('start_time', $date);

        if ($request->filled('clinic_id')) {
            $appointments->where('clinic_id', $request->clinic_id);
        }

        if ($request->filled('patient_id')) {
            $appointments->where('patient_id', $request->patient_id);
        }

        $appointments = $appointments->get();

        $appointment_types = $this->getDropdownOptions('APPOINTMENT_TYPE');
        
        $user = auth()->user();
        if ($user->hasRole('patient')) {
            $patients = Patient::with('title')->where('id', $user->userable_id)->paginate(1);
        } else {
            $patients = Patient::all();
        }

        return view('planner.index', [
            'appointments' => $appointments,
            'date' => $date,
            'clinics' => Clinic::orderBy('planner_seq', 'asc')->get(),
            'patients' => $patients, 
            'appointmentTypes' => $appointment_types,
            'diary_status' => $diary_status,
            'procedures' => $procedures
        ]);
    }

}
