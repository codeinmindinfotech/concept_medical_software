<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use DropdownTrait;

    public function patientSchedulePage(Patient $patient)
    {
        $clinics = Clinic::all();
        $appointment_types = $this->getDropdownOptions('APPOINTMENT_TYPE');
        return view('patients.appointments.patient-schedule', compact('clinics', 'patient','appointment_types'));
    }

    public function getAppointmentsByDate(Request $request, Patient $patient)
    {
        try {
            $request->validate([
                'clinic_id' => 'nullable|exists:clinics,id',
                'date' => 'required|date',
            ]);

            $appointmentsQuery = Appointment::with('appointmentType','patient')
                // ->where('patient_id', $patient->id)
                ->whereDate('appointment_date', $request->date);

            if ($request->filled('clinic_id')) {
                $appointmentsQuery->where('clinic_id', $request->clinic_id);
                $clinic = Clinic::findOrFail($request->clinic_id);
            } else {
                $clinic = null; // No specific clinic selected
            }

            $appointments = $appointmentsQuery->get();

            return response()->json([
                'appointments' => $appointments,
                'clinic' => $clinic,
                'patient' => $patient,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }  

    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_type' => 'nullable|exists:drop_down_values,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'apt_slots' => 'required|integer|min:1|max:10',
        ]);

        $clinic = Clinic::findOrFail($request->clinic_id);
        $dayName = strtolower(Carbon::parse($request->appointment_date)->format('D'));
        $interval = $clinic->{$dayName . '_interval'} ?? 15;

        $slots = $request->apt_slots;
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);

        $now = now();
        $appointments = [];

        for ($i = 0; $i < $slots; $i++) {
            $slotStart = $startTime->copy()->addMinutes($i * $interval);
            $slotEnd = $slotStart->copy()->addMinutes($interval);

            $appointments[] = [
                'patient_id' => $patient->id,
                'clinic_id' => $request->clinic_id,
                'appointment_type' => $request->appointment_type,
                'appointment_date' => $request->appointment_date,
                'start_time' => $slotStart->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'apt_slots' => 1,
                'patient_need' => $request->patient_need,
                'appointment_note' => $request->appointment_note,
                'arrival_time' => $request->arrival_time,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Store in DB
        Appointment::insert($appointments);

        return response()->json(['success' => true]);
    }

    
}
