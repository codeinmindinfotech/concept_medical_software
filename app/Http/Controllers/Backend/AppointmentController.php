<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ChargeCode;
use App\Models\Clinic;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    use DropdownTrait;

    public function schedulePage(Request $request, ?Patient $patient = null)
    {
        $this->authorize('viewAny', Appointment::class);
        if (has_role('patient')) {
            $user = auth()->user();
            $patients = Patient::companyOnly()->with('title')->where('id', $user->id)->paginate(1);
        } else {
            $patients = Patient::companyOnly()->with(['title', 'preferredContact'])->get();
        }
        
        $clinics = Clinic::companyOnly()->get()->map(function ($clinic) {
            return $clinic;
        });

        $appointmentTypes = $this->getDropdownOptions('APPOINTMENT_TYPE');
        $diary_status = $this->getDropdownOptions('DIARY_CATEGORIES');
        $procedures = ChargeCode::companyOnly()->get();
        
        return view(guard_view('patients.appointments.patient-schedule', 'patient_admin.appointment.patient-schedule'), compact('procedures','patients','diary_status','clinics', 'patient', 'appointmentTypes'));
    }

    public function calendarEvents(Request $request, ?Patient $patient = null)
    { 
        $this->authorize('viewAny', Appointment::class);
        $clinicId = $request->input('clinic_id');
        $patientId = $request->input('patient_id');
        $query = Appointment::companyOnly()->with(['patient', 'clinic']); // Eager load relations

        if (isset($patientId)) {
            $query->where('patient_id', $patientId);
        }
        if ($clinicId) {
            $query->where('clinic_id', $clinicId);
        }

        $appointments = $query->get()->map(function ($appointment) {
            return [
                'title' => optional($appointment->patient)->full_name . " " . $appointment->start_time . "-" . $appointment->end_time,
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

    public function getAppointmentsByDate(Request $request, Patient $patient = null)
    {
        $this->authorize('viewAny', Appointment::class);
        $flag = $request->route('flag'); 
        try {
            $request->validate([
                'clinic_id' => 'nullable|exists:clinics,id',
                'date' => 'required|date',
            ]);

            if ($flag == 1 && !$patient) {
                $patient = null;
            }
            
            $appointmentsQuery = Appointment::companyOnly()->with('appointmentType', 'patient','appointmentStatus','clinic')
                ->whereDate('appointment_date', $request->date);

            if ($request->filled('patientSelect')) {
                $appointmentsQuery->where('patient_id', $request->patientSelect);
            }

            $clinic = null;
            
            if ($request->filled('clinic_id')) {
                $appointmentsQuery->where('clinic_id', $request->clinic_id);
                $clinic = Clinic::companyOnly()->findOrFail($request->clinic_id);

                // Check clinic type and route accordingly
                if (strtolower($clinic->clinic_type) === 'hospital') {
                    return $this->getHospitalAppointmentsByDate($request, $patient, $clinic);
                }
            }

            $appointments = $appointmentsQuery->get();

            // Determine working day schedule
            if (!$clinic) {
                return response()->json([
                    'html' => '<tr><td class="text-center text-muted" colspan="8">No clinic selected</td></tr>',
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
                        <td colspan="8">
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

    private function getHospitalAppointmentsByDate(Request $request, ?Patient $patient, Clinic $clinic)
    {
        $this->authorize('viewAny', Appointment::class);
        $isOpen = 0;
        $flag = $request->route('flag'); 

        // Determine working day schedule
        if (!$clinic) {
            return response()->json([
                'html' => '<tr><td class="text-center text-muted" colspan="8">No Hospital selected</td></tr>',
            ]);
        }
        
        $hospitalAppointmentsQuery = Appointment::companyOnly()->with(['patient', 'appointmentType','appointmentStatus','procedure'])
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

        $clinic = Clinic::companyOnly()->findOrFail($request->clinic_id);

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
                'company_id' => $appointment->company_id?? current_company_id(),
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
                'company_id' => current_company_id(),
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
                'company_id' => $hospitalAppointment->company_id?? current_company_id(),
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
            'company_id' => current_company_id(),
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

        $appointment = Appointment::companyOnly()->findOrFail($request->appointment_id);

        $date = Carbon::parse($appointment->appointment_date);

        $newStart = $date->copy()->setTimeFromTimeString($request->new_time)->setSeconds(0);
        $newEnd = $newStart->copy()->addMinutes(15); // End cleanly at next 15 min mark

        $isSlotTaken = Appointment::companyOnly()->where('appointment_date', $appointment->appointment_date)
            ->where('id', '!=', $appointment->id)
            ->where('clinic_id', '=', $appointment->clinic_id)
            ->where(function ($query) use ($newStart, $newEnd) {
                $query->whereRaw('TIME(start_time) < ?', [$newEnd->format('H:i:s')])
                    ->whereRaw('TIME(end_time) > ?', [$newStart->format('H:i:s')]);
            })
            ->exists();
        
        if ($isSlotTaken) {
            return response()->json([
                'success' => false,
                'message' => 'The selected time slot is already booked.',
            ]);
        }

        $appointment->start_time = $newStart;
        $appointment->end_time = $newEnd;
        $appointment->save();

        return response()->json([
            'success' => true,
            'message' => 'Appointment updated successfully.',
        ]);
    
    }

    public function clinicOverviewCounts(Request $request)
    {
        $user = current_user();
        $isSuperAdmin = $user->hasRole('superadmin');
        // $selectedDate = $request->input('date');
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

            $query = Appointment::with('clinic')->select(
                    'appointments.clinic_id',
                    'clinics.name as clinic_name',
                    DB::raw('DATE(appointments.appointment_date) as appointment_date'),
                    DB::raw('COUNT(*) as appointment_count')
                )
                ->join('clinics', 'appointments.clinic_id', '=', 'clinics.id')
                ->whereBetween('appointments.appointment_date', [$startOfMonth, $endOfMonth]);
                if (! $isSuperAdmin) {
                    $query->where('appointments.company_id', current_company_id());
                }
                $query->groupBy('appointments.clinic_id', 'clinics.name', DB::raw('DATE(appointments.appointment_date)'))
                        ->orderBy('clinics.name')
                        ->orderBy('appointment_date');
                $appointmentCounts = $query->get();

        return view('patients.appointments.clinic_overview_counts', compact('appointmentCounts'))->render();
    }

    public function availableSlots(Request $request)
    {
        $request->validate([
            'clinic_id' => 'required|integer',
            'date' => 'required|date',
        ]);

        try {
            $clinicId = $request->clinic_id;
            $date = Carbon::parse($request->date)->format('Y-m-d');
            $dayOfWeek = strtolower(Carbon::parse($date)->format('D')); // mon, tue, wed, etc.

            $clinic = Clinic::findOrFail($clinicId);

            // Check if clinic is active on that day
            $isActive = $clinic->{$dayOfWeek}; // 1 or 0
            if (!$isActive) {
                return response()->json([
                    'success' => true,
                    'slots' => [],
                    'message' => 'Clinic is closed on this day.'
                ]);
            }

            $interval = $clinic->{$dayOfWeek . '_interval'} ?? 30; // default 30 minutes
            $slots = [];

            // Morning session
            $morningStart = $clinic->{$dayOfWeek . '_start_am'};
            $morningEnd   = $clinic->{$dayOfWeek . '_finish_am'};

            if ($morningStart && $morningEnd) {
                $slots = array_merge($slots, $this->generateTimeSlotsForClinic($date, $morningStart, $morningEnd, $interval, $clinicId));
            }

            // Afternoon session
            $afternoonStart = $clinic->{$dayOfWeek . '_start_pm'};
            $afternoonEnd   = $clinic->{$dayOfWeek . '_finish_pm'};

            if ($afternoonStart && $afternoonEnd) {
                $slots = array_merge($slots, $this->generateTimeSlotsForClinic($date, $afternoonStart, $afternoonEnd, $interval, $clinicId));
            }

            return response()->json([
                'success' => true,
                'slots' => $slots
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch available slots.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function generateTimeSlotsForClinic($date, $startTime, $endTime, $interval, $clinicId)
    {
        // Ensure start/end are times only
        $startTime = Carbon::parse($startTime)->format('H:i:s');
        $endTime   = Carbon::parse($endTime)->format('H:i:s');

        $start = Carbon::parse("$date $startTime");
        $end   = Carbon::parse("$date $endTime");

        $slots = [];

        for ($time = $start; $time < $end; $time->addMinutes($interval)) {
            // Check if slot is already booked
            $exists = Appointment::where('clinic_id', $clinicId)
                ->where('appointment_date', $date)
                ->where('start_time', '<=', $time->format('H:i:s'))
                ->where('end_time', '>', $time->format('H:i:s'))
                ->companyOnly()
                ->exists();

            if (!$exists) {
                $slots[] = $time->format('H:i');
            }
        }

        return $slots;
    }

    public function move(Request $request)
    {
        $request->validate([
            'appointment_ids' => 'required|array|min:1',
            'appointment_ids.*' => 'integer|exists:appointments,id',
            'new_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
            'clinic_id' => 'required|integer|exists:clinics,id',
            'time_slot' => 'required|string', // e.g. "16:00"
        ]);

        try {
            DB::beginTransaction();

            $appointmentIds = $request->appointment_ids;
            $newDate = Carbon::parse($request->new_date);
            $reason = $request->reason;
            $clinicId = $request->clinic_id;
            $selectedSlot = $request->time_slot;

            // ✅ Get the clinic and its interval based on weekday
            $clinic = Clinic::findOrFail($clinicId);
            $dayName = strtolower($newDate->format('D')); // mon, tue, wed, etc.
            $intervalColumn = $dayName . '_interval';
            $isActiveColumn = $dayName; // e.g. mon, tue...

            // Check if clinic is open that day
            if (!$clinic->$isActiveColumn) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Clinic is closed on " . ucfirst($dayName) . "."
                ]);
            }

            // ✅ Determine interval in minutes
            $interval = $clinic->$intervalColumn ?? 30; // default 30 if null

            // ✅ Compute start/end time
            $newStart = Carbon::parse($selectedSlot);
            $newEnd   = $newStart->copy()->addMinutes($interval);

            $newStartTime = $newStart->format('H:i:s');
            $newEndTime   = $newEnd->format('H:i:s');

            foreach ($appointmentIds as $id) {
                $appointment = Appointment::findOrFail($id);

                // ✅ Conflict check
                $conflict = Appointment::where('clinic_id', $clinicId)
                    ->where('appointment_date', $newDate->toDateString())
                    ->where(function ($q) use ($newStartTime, $newEndTime) {
                        $q->whereBetween('start_time', [$newStartTime, $newEndTime])
                        ->orWhereBetween('end_time', [$newStartTime, $newEndTime])
                        ->orWhere(function ($q2) use ($newStartTime, $newEndTime) {
                            $q2->where('start_time', '<', $newStartTime)
                                ->where('end_time', '>', $newEndTime);
                        });
                    })
                    ->companyOnly()
                    ->where('id', '!=', $appointment->id)
                    ->exists();

                if ($conflict) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected time slot is already booked.',
                    ]);
                }

                // ✅ Update appointment
                $appointment->clinic_id = $clinicId;
                $appointment->appointment_date = $newDate->toDateString();
                $appointment->start_time = $newStartTime;
                $appointment->end_time = $newEndTime;
                $appointment->move_reason = $reason;
                $appointment->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Appointments moved successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to move appointments.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAppointmentsForDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'clinic_id' => 'required|integer',
        ]);

        try {
            $appointments = Appointment::with([
                'patient',          // for patient info
                'appointmentType',  // type
                'appointmentStatus' // status
            ])
            ->whereDate('appointment_date', $request->date)
            ->where('clinic_id', $request->clinic_id)
            ->orderBy('start_time')
            ->companyOnly()
            ->get();

            // Render Blade partial for each appointment
            $html = '';
            foreach ($appointments as $appointment) {
                $time = \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') . ' - ' . 
                        \Carbon\Carbon::parse($appointment->end_time)->format('h:i A');

                $rowColor = $appointment->appointmentStatus->value ?? '#0d6efd';

                $html .= view('patients.appointments.appointment_card', compact('appointment', 'time', 'rowColor'))->render();
            }

            return response()->json([
                'success' => true,
                'appointments_html' => $html
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch appointments.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkSlot(Request $request)
    {
        $appointmentId = $request->appointment_id;
    
        $exists = Appointment::where('appointment_date', $request->date)
            ->where('id', '!=', $appointmentId)
            ->where(function ($q) use ($request) {
    
                // Rule 1: Clinic cannot double-book same time
                $q->where('clinic_id', $request->clinic_id)
                  ->orWhere('patient_id', $request->patient_id); // Rule 2: Patient cannot double book
            })
            ->where(function($q) use ($request) {
                // Time overlap logic
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function ($q2) use ($request) {
                        // Full overlap case
                        $q2->where('start_time', '<', $request->start_time)
                           ->where('end_time', '>', $request->end_time);
                  });
            })
            ->exists();
    
        return response()->json(['available' => !$exists]);
    }
    

    // public function checkSlot(Request $request)
    // {
    //     $exists = Appointment::where('clinic_id', $request->clinic_id)
    //         ->where('appointment_date', $request->date)
    //         ->where('id', '!=', $request->appointment_id)
    //         ->where(function($q) use ($request) {
    //             $q->whereBetween('start_time', [$request->start_time, $request->end_time])
    //             ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
    //         })
    //         ->exists();

    //     return response()->json(['available' => !$exists]);
    // }

    public function updateTime(Request $request, Appointment $appointment)
    {
        $appointment->update([
            'appointment_date' => $request->date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        return response()->json(['success' => true]);
    }


}