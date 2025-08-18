<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ChargeCode;
use App\Models\Clinic;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PlannerController extends Controller
{
    use DropdownTrait;
    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $diary_status = $this->getDropdownOptions('DIARY_CATEGORIES');
        $procedures = ChargeCode::all();
        $appointments = Appointment::with(['patient', 'appointmentType','appointmentStatus','procedure'])
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
            $patients = Patient::with(['title','preferredContact'])->where('id', $user->userable_id)->paginate(1);
        } else {
            $patients = Patient::with(['title', 'preferredContact'])->get();
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

    public function reschedule(Request $request, Appointment $appointment)
    {
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'hour' => 'required|integer|min:0|max:23',
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date);
        $start = $date->copy()->setTime($request->hour, 0);
        $end = $start->copy()->addMinutes(15); // or your appointment duration
        
        $appointment->update([
            'clinic_id' => $request->clinic_id,
            'start_time' => $start,
            'end_time' => $end,
            'appointment_type' => $request->appointment_type,
            'procedure_id' => $request->procedure_id
        ]);

        return response()->json(['success' => true]);
    }

}
