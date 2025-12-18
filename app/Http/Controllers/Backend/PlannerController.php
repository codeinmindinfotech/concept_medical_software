<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
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
        $procedures = ChargeCode::companyOnly()->get();
        $appointments = Appointment::companyOnly()->with(['patient', 'appointmentType','appointmentStatus','procedure'])
            ->whereDate('appointment_date', $date);

        if ($request->filled('clinic_id')) {
            $appointments->where('clinic_id', $request->clinic_id);
        }

        if ($request->filled('patient_id')) {
            $appointments->where('patient_id', $request->patient_id);
        }

        $appointments = $appointments->get();

        $appointment_types = $this->getDropdownOptions('APPOINTMENT_TYPE');
        
        
        if (has_role('patient')) {
            $user = auth()->user();
            $patients = Patient::companyOnly()->with(['title','preferredContact'])->where('id', $user->id)->paginate(1);
        } else {
            $patients = Patient::companyOnly()->with(['title', 'preferredContact'])->get();
        }
        $patient = null; // Pass blank $patient
        
        return view(guard_view('planner.index', 'patient_admin.planner.index'), [
            'appointments' => $appointments,
            'date' => $date,
            'clinics' => Clinic::companyOnly()->orderBy('planner_seq', 'asc')->get(),
            'patients' => $patients, 
            'patient' => $patient, 
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

        $duration = 15;
        $date = Carbon::parse($request->date)->startOfDay(); // e.g., 2025-08-18 00:00:00
        $start = $date->copy()->setTime($request->hour, 0);
        $latestStart = $date->copy()->setTime(23, 45); // Last possible start time
        $end = $start->copy()->addMinutes($duration);

        while (
            Appointment::where('clinic_id', $request->clinic_id)
                ->where('id', '!=', $appointment->id) // Skip the current appointment
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start_time', [$start, $end->copy()->subSecond()])
                        ->orWhereBetween('end_time', [$start->copy()->addSecond(), $end]);
                })
                ->companyOnly()
                ->exists()
        ) {
            $start->addMinutes($duration);
            $end->addMinutes($duration);

            if ($start->gt($latestStart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No available time slots for this day.'
                ], 409); // Conflict
            }
        }

        $appointment->update([
            'clinic_id' => $request->clinic_id,
            'start_time' => $start,
            'end_time' => $end,
            'appointment_type' => $request->appointment_type,
            'procedure_id' => $request->procedure_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => "updated..."
        ]);
    }


}
