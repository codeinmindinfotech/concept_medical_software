<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    use DropdownTrait;

    public function patientSchedulePage(Patient $patient)
    {
        $clinics = Clinic::all()->map(function ($clinic) {
            $clinic->color = '#'.substr(md5($clinic->id), 0, 6); // assign hex color
            return $clinic;
        });
        $appointment_types = $this->getDropdownOptions('APPOINTMENT_TYPE');
        $diary_status = $this->getDropdownOptions('DIARY_CATEGORIES');
        return view('patients.appointments.patient-schedule', compact('diary_status','clinics', 'patient', 'appointment_types'));
    }

    public function calendarEvents(Request $request, Patient $patient)
    {
        $appointments = Appointment::where('patient_id', $patient->id)
            ->when($request->clinic_id, function($q) use ($request) {
                $q->where('clinic_id', $request->clinic_id);
            })
            ->get()
            ->map(function($appointment) {
                return [
                    'title' => '✔',
                    'start' => $appointment->appointment_date,
                    'allDay' => true,
                    'color' => $appointment->clinic->color ?? '#3788d8', // assign a clinic-specific color
                ];
            });

        return response()->json($appointments);
    }

    public function getAppointmentsByDate(Request $request, Patient $patient)
    {
        try {
            $request->validate([
                'clinic_id' => 'nullable|exists:clinics,id',
                'date' => 'required|date',
            ]);

            $appointmentsQuery = Appointment::with('appointmentType', 'patient')
                ->whereDate('appointment_date', $request->date);

            $clinic = null;

            if ($request->filled('clinic_id')) {
                $appointmentsQuery->where('clinic_id', $request->clinic_id);
                $clinic = Clinic::findOrFail($request->clinic_id);
            }

            $appointments = $appointmentsQuery->get();

            // Determine working day schedule
            if (!$clinic) {
                return response()->json([
                    'html' => '<tr><td colspan="6">No clinic selected</td></tr>',
                ]);
            }

            $date = Carbon::parse($request->date);
            $day = strtolower($date->format('D')); // mon, tue, wed...
            $dayMap = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            $dayKey = $dayMap[$date->dayOfWeek];

            // Clinic open/close check
            if (!$clinic->$dayKey) {
                return response()->json([
                    'html' => '<tr><td colspan="6">Clinic is closed on this day.</td></tr>',
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
                'html' => view('patients.appointments.slot_table_rows', [
                    'appointments' => $appointments,
                    'slots' => $slots,
                    'patient' => $patient
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
        $validator = Validator::make($request->all(), [
            'clinic_id' => 'required|exists:clinics,id',
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

        if ($request->filled('appointment_id')) {
            $appointment = Appointment::find($request->appointment_id);

            if (!$appointment) {
                return response()->json(['error' => 'Appointment not found.'], 404);
            }

            $endTime = $startTime->copy()->addMinutes($interval);

            $appointment->update([
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

        Appointment::insert($appointments);

        return response()->json(['success' => true, 'message' => 'Appointment(s) created']);
    }

    public function destroy(Patient $patient, Appointment $appointment)
    {
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


}
