<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\CalendarDay;
use Illuminate\View\View;
use App\Models\Appointment;
use App\Models\ChargeCode;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Carbon\Carbon;

class CalendarController extends Controller
{
    use DropdownTrait;

    public function store(Request $request)
    {
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'dates' => 'required|array|min:1',
        ]);

        foreach ($request->dates as $date) {
            CalendarDay::updateOrCreate(
                ['clinic_id' => $request->clinic_id, 'date' => $date],
                ['is_active' => true]
            );
        }

        return response()->json(['success' => true, 'message' => 'Calendar days saved successfully!']);
    }

    public function fetchDays(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
            'clinic_id' => 'required|integer',
        ]);
    
        $days = CalendarDay::with('clinic:id,name,color')
            ->whereBetween('date', [$request->start, $request->end])
            ->where('clinic_id', $request->clinic_id)
            ->companyOnly()
            ->get();
    
        return response()->json(
            $days->map(function ($day) {
                return [
                    'start' => $day->date,
                    'end' => $day->date,
                    'display' => 'background',
                    'backgroundColor' => 'transparent', // or $day->clinic->color
                    'borderColor' => $day->clinic->color ?? '#3aa757',
                    'title' => '',
                ];
            })
        );
    }
    

    public function index(Request $request, ?Patient $patient = null): View|string
    {
        $this->authorize('viewAny', Appointment::class);
        $patients = Patient::companyOnly()->latest()->get();
        $clinics = Clinic::companyOnly()->orderBy('planner_seq', 'asc')->get();
        $appointmentTypes = $this->getDropdownOptions('APPOINTMENT_TYPE');
        $diary_status = $this->getDropdownOptions('DIARY_CATEGORIES');
        $procedures = ChargeCode::companyOnly()->get();
        return view('patient_admin.calendar', compact('procedures','patients','diary_status','clinics', 'patient', 'appointmentTypes'));   
    }

  
    public function appointmentindex(Request $request)
    {
        $query = Appointment::query();

        if ($request->has('clinic_id') && $request->clinic_id != '') {
            $query->where('clinic_id', $request->clinic_id);
        }

        $appointments = $query->get();
        $events = $appointments->map(function($apt) {
            return [
                'id' => $apt->id,
                'title' => $apt->patient->fullname,
                'start' => $apt->appointment_date . 'T' . $apt->start_time,
                'end' => $apt->appointment_date . 'T' . $apt->end_time,
                'clinic_id' => $apt->clinic_id,
                'clinic_type' => $apt->clinic->clinic_type ?? 'clinic',
                'color' => $apt->clinic->color ?? '#007bff',
                'patient_id' => $apt->patient_id,
                'patient_name' => $apt->patient->fullname,
                'dob' => format_date($apt->patient->dob),
                'consultant' => $apt->patient->consultant->name ?? '',
                'appointment_type' => $apt->appointment_type,
                'apt_slots' => $apt->apt_slots,
                'patient_need' => $apt->patient_need,
                'appointment_note' => $apt->appointment_note,
                // hospital
                'procedure_id' => $apt->procedure_id,
                'admission_date' => $apt->admission_date,
                'admission_time' => $apt->admission_time,
                'operation_duration' => $apt->operation_duration,
                'ward' => $apt->ward,
                'allergy' => $apt->allergy,
                'color' => $apt->clinic->color ?? '#007bff'
            ];
        });
        return response()->json($events);
    }
    
    public function appointmentstore(Request $request)
    {
        $apt = Appointment::create([
            'title' => $request->title,
            'start_time' => $request->start,
            'end_time' => $request->end,
            'category' => $request->category
        ]);

        return response()->json($apt);
    }
    
    public function update(Request $request, $id)
    {
        $apt = Appointment::findOrFail($id);
        $apt->update([
            'title' => $request->title,
            'start_time' => $request->start,
            'end_time' => $request->end,
            'category' => $request->category
        ]);

        return response()->json($apt);
    }
    
    public function destroy($id)
    {
        $apt = Appointment::findOrFail($id);
        $apt->delete();

        return response()->json(['success' => true]);
    }

    
}