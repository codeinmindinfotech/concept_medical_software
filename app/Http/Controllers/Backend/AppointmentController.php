<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ChargeCode;
use App\Models\Clinic;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    use DropdownTrait;

    public function schedulePage(Request $request, ?Patient $patient = null)
    {
        $this->authorize('viewAny', Appointment::class);
        $user = auth()->user();
        if ($user->hasRole('patient')) {
            $patients = Patient::with('title')->where('id', $user->userable_id)->paginate(1);
        } else {
            $patients = Patient::with(['title', 'preferredContact'])->get();
        }
        
        $clinics = Clinic::all()->map(function ($clinic) {
            $clinic->color = '#'.substr(md5($clinic->id), 0, 6); // assign hex color
            return $clinic;
        });
        $appointmentTypes = $this->getDropdownOptions('APPOINTMENT_TYPE');
        $diary_status = $this->getDropdownOptions('DIARY_CATEGORIES');
        $procedures = ChargeCode::all();
        return view('patients.appointments.patient-schedule', compact('procedures','patients','diary_status','clinics', 'patient', 'appointmentTypes'));
    }

    public function calendarEvents(Request $request, ?Patient $patient = null)
    {
        $this->authorize('viewAny', Appointment::class);
        $clinicId = $request->input('clinic_id');
        $patientId = $request->input('patient_id');
        $query = Appointment::with(['patient', 'clinic']); // Eager load relations

        if (isset($patientId)) {
            $query->where('patient_id', $patientId);
        }
        if ($clinicId) {
            $query->where('clinic_id', $clinicId);
        }

        $appointments = $query->get()->map(function ($appointment) {
            return [
                'title' => '✔ ' . optional($appointment->patient)->full_name . " " . $appointment->start_time . "-" . $appointment->end_time,
                'borderColor' => optional($appointment->clinic)->color ?? '#000000',
                'start' => $appointment->appointment_date . 'T' . format_time($appointment->start_time),
                // 'end' => $appointment->appointment_date . 'T' . $appointment->end_time,
                'allDay' => false,
                // 'color' => '#ffffff', // white background
                'extendedProps' => [
                    'clinicColor' => $appointment->clinic->color ?? '#ffffff',
                ],
            ];
        });

        return response()->json($appointments);
    }

    public function getAppointmentsByDate(Request $request, Patient $patient)
    {
        $this->authorize('viewAny', Appointment::class);
        $flag = $request->route('flag'); 
        try {
            $request->validate([
                'clinic_id' => 'nullable|exists:clinics,id',
                'date' => 'required|date',
            ]);

            $appointmentsQuery = Appointment::with('appointmentType', 'patient','appointmentStatus')
                ->whereDate('appointment_date', $request->date);

            if ($request->filled('patientSelect')) {
                $appointmentsQuery->where('patient_id', $request->patientSelect);
            }

            $clinic = null;

            if ($request->filled('clinic_id')) {
                $appointmentsQuery->where('clinic_id', $request->clinic_id);
                $clinic = Clinic::findOrFail($request->clinic_id);

                // Check clinic type and route accordingly
                if (strtolower($clinic->clinic_type) === 'hospital') {
                    return $this->getHospitalAppointmentsByDate($request, $patient, $clinic);
                }
            }

            $appointments = $appointmentsQuery->get();

            // Determine working day schedule
            if (!$clinic) {
                return response()->json([
                    'html' => '<tr><td class="text-center text-muted" colspan="7">No clinic selected</td></tr>',
                ]);
            }

            $date = Carbon::parse($request->date);
            $day = strtolower($date->format('D')); 
            $dayMap = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            $dayKey = $dayMap[$date->dayOfWeek];

            // Clinic open/close check
            if (!$clinic->$dayKey) {
                return response()->json([
                    'html' => '<tr>
                        <td colspan="7">
                            <div class="alert alert-warning d-flex align-items-center justify-content-center mb-0 py-4 rounded-3 shadow-sm" role="alert" id="close_clinic">
                                <i class="fas fa-exclamation-triangle me-2 fs-5 text-warning"></i>
                                <strong class="me-1">Clinic Closed:</strong> The clinic is closed on this date.
                            </div>
                        </td>
                    </tr>',
                ]);
            }

            $interval = intval($clinic->{$dayKey . '_interval'} ?? 15);
            $slots = [];

            // Morning and afternoon time slots
            if ($clinic->{$dayKey . '_start_am'} && $clinic->{$dayKey . '_finish_am'}) {
                $slots = array_merge($slots, $this->generateTimeSlots(
                    $clinic->{$dayKey . '_start_am'},
                    $clinic->{$dayKey . '_finish_am'},
                    $interval
                ));
            }

            if ($clinic->{$dayKey . '_start_pm'} && $clinic->{$dayKey . '_finish_pm'}) {
                $slots = array_merge($slots, $this->generateTimeSlots(
                    $clinic->{$dayKey . '_start_pm'},
                    $clinic->{$dayKey . '_finish_pm'},
                    $interval
                ));
            }

            $stats = $appointments->groupBy(fn($apt) => optional($apt->appointmentType)->value)
            ->map(fn($group) => $group->count());
                return response()->json([
                    'html' => view('patients.appointments.slot_table_clinic', [
                        'appointments' => $appointments,
                        'slots' => $slots,
                        'patient' => $patient,
                        'flag' => $flag
                    ])->render(),
                    'stats' => [
                        'total' => count($appointments),
                        'byType' => $stats,
                    ],
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getHospitalAppointmentsByDate(Request $request, Patient $patient, Clinic $clinic)
    {
        $this->authorize('viewAny', Appointment::class);
        $isOpen = 0;
        $flag = $request->route('flag'); 

        // Determine working day schedule
        if (!$clinic) {
            return response()->json([
                'html' => '<tr><td class="text-center text-muted" colspan="7">No clinic selected</td></tr>',
            ]);
        }
        
        $hospitalAppointmentsQuery = Appointment::with(['patient', 'appointmentType','appointmentStatus','procedure'])
            ->where('clinic_id', $clinic->id)
            ->whereDate('appointment_date', $request->date);

        if ($request->filled('patient_id')) {
            $hospitalAppointmentsQuery->where('patient_id', $request->patient_id);
        }

        $hospitalAppointments = $hospitalAppointmentsQuery->get();

        $stats = $hospitalAppointments->groupBy(fn($apt) => optional($apt->procedure)->code)
            ->map(fn($group) => $group->count());

        $date = Carbon::parse($request->date);
        $dayField = strtolower($date->format('D')); 

        $clinic = Clinic::findOrFail($request->clinic_id);

        $isOpen = $clinic->{$dayField}; 

        return response()->json([
            'html' => view('patients.appointments.slot_table_hospital', [
                'appointments' => $hospitalAppointments,
                'patient' => $patient,
                'isOpen' => $isOpen,
                'flag'  => $flag
            ])->render(),
            'isOpen' => $isOpen,
            'stats' => [
                'total' => count($hospitalAppointments),
                'byType' => $stats,
            ],
        ]);
    }


    protected function generateTimeSlots($start, $end, $interval)
    {
        $slots = [];

        [$sh, $sm] = array_map('intval', explode(':', $start));
        [$eh, $em] = array_map('intval', explode(':', $end));

        while ($sh < $eh || ($sh === $eh && $sm < $em)) {
            $slots[] = sprintf('%02d:%02d', $sh, $sm);
            $sm += $interval;

            if ($sm >= 60) {
                $sh += intdiv($sm, 60);
                $sm %= 60;
            }
        }

        return $slots;
    }

    public function store(Request $request, Patient $patient)
    {
        $this->authorize('create', Appointment::class);
        $flag = $request->route('flag'); 
        $validator = Validator::make($request->all(), [
            'clinic_id' => 'required|exists:clinics,id',
            'patient_id' => ($flag == 0) ? 'nullable' : 'required|exists:patients,id',
            'appointment_type' => 'required|exists:drop_down_values,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'apt_slots' => 'required|integer|min:1|max:10',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $clinic = Clinic::findOrFail($request->clinic_id);
        $dayName = strtolower(Carbon::parse($request->appointment_date)->format('D'));
        $interval = $clinic->{$dayName . '_interval'} ?? 15;

        $slots = $request->apt_slots;
        $startTimeRaw = $request->start_time;
        $startTime = Carbon::createFromFormat('H:i', substr($startTimeRaw, 0, 5));

        $now = now();
        $patientId = $request->patient_id ? $request->patient_id : $patient->id;

        if (!$patientId) {
            return response()->json([
                'success' => false,
                'errors' => 'Patient ID is required.'
            ], 422);
        }

        if ($request->filled('appointment_id')) {
            $appointment = Appointment::find($request->appointment_id);

            if (!$appointment) {
                return response()->json(['error' => 'Appointment not found.'], 404);
            }

            $endTime = $startTime->copy()->addMinutes($interval);

            $appointment->update([
                'patient_id' =>$patientId,
                'clinic_id' => $request->clinic_id,
                'appointment_type' => $request->appointment_type,
                'appointment_date' => $request->appointment_date,
                'start_time' => $startTime->format('H:i'),
                'end_time' => $endTime->format('H:i'),
                'apt_slots' => 1,
                'patient_need' => $request->patient_need,
                'appointment_note' => $request->appointment_note,
                'updated_at' => $now,
            ]);

            return response()->json(['success' => true, 'message' => 'Appointment updated']);
        }

        $appointments = [];

        for ($i = 0; $i < $slots; $i++) {
            $slotStart = $startTime->copy()->addMinutes($i * $interval);
            $slotEnd = $slotStart->copy()->addMinutes($interval);

            $appointments[] = [
                'patient_id' =>$patientId,
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

        Appointment::insert($appointments);

        return response()->json(['success' => true, 'message' => 'Appointment(s) created']);
    }

    public function storeHospitalAppointment(Request $request, Patient $patient)
    {
        $this->authorize('create', Appointment::class);
        $flag = $request->route('flag'); 
        $validator = Validator::make($request->all(), [
            'clinic_id' => 'required|exists:clinics,id',
            'patient_id' => ($flag == 0) ? 'nullable' : 'required|exists:patients,id',
            'procedure_id' => 'required|exists:charge_codes,id',  // Adjust if your foreign key table is different
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'admission_date' => 'required|date',
            'admission_time' => 'required|date_format:H:i',
            'operation_duration' => 'required|integer|min:1',  // duration in minutes
            'ward' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'allergy' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $now = now();
        $patientId = $request->patient_id ? $request->patient_id : $patient->id;

        if (!$patientId) {
            return response()->json([
                'success' => false,
                'errors' => 'Patient ID is required.'
            ], 422);
        }
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $duration = intval($request->operation_duration ?? 30); // or any default value
        $endTime = $startTime->copy()->addMinutes($duration);

        if ($request->filled('hospital_id')) {
            $hospitalAppointment = Appointment::find($request->hospital_id);

            if (!$hospitalAppointment) {
                return response()->json(['error' => 'Hospital Appointment not found.'], 404);
            }

            $hospitalAppointment->update([
                'patient_id' =>$patientId,
                'clinic_id' => $request->clinic_id,
                'procedure_id' => $request->procedure_id,
                'appointment_date' => $request->appointment_date,
                'start_time' => $request->start_time,
                'end_time' => $endTime,
                'admission_date' => $request->admission_date,
                'admission_time' => $request->admission_time,
                'operation_duration' => $request->operation_duration,
                'ward' => $request->ward,
                'appointment_note' => $request->notes,
                'allergy' => $request->allergy,
                'updated_at' => $now,
            ]);

            return response()->json(['success' => true, 'message' => 'Hospital appointment updated']);
        }

        $hospitalAppointment = Appointment::create([
            'patient_id' =>$patientId,
            'clinic_id' => $request->clinic_id,
            'procedure_id' => $request->procedure_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $endTime,
            'admission_date' => $request->admission_date,
            'admission_time' => $request->admission_time,
            'operation_duration' => $request->operation_duration,
            'ward' => $request->ward,
            'appointment_note' => $request->notes,
            'allergy' => $request->allergy,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json(['success' => true, 'message' => 'Hospital appointment created', 'id' => $hospitalAppointment->id]);
    }

    public function destroy(Patient $patient, Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        try {
            if ($appointment->patient_id !== $patient->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized appointment access.'
                ], 403);
            }
            $appointment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Appointment deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete appointment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $patientId, Appointment $appointment)
    {
        $this->authorize('create', Appointment::class);
        $validator = Validator::make($request->all(), [
            'appointment_status' => 'required|exists:drop_down_values,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $appointment->appointment_status = $request->input('appointment_status');
        $appointment->save();

        return response()->json([
            'success' => true,
            'message' => 'Appointment status updated successfully.',
            'status' => $appointment->appointment_status,
        ]);
    }
    public function updateSlot(Request $request)
    {
        $this->authorize('create', Appointment::class);
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'new_time' => 'required|date_format:H:i',
        ]);
    
        $appointment = Appointment::find($request->appointment_id);
        $appointment->start_time = $request->new_time;
    
        // You can also recalculate end_time if needed here
        $appointment->save();
        return response()->json([
            'success' => true,
            'message' => 'Appointment updated successfully.',
        ]);
    }
}
