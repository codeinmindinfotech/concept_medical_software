<?php

namespace App\Http\Controllers\patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request, Patient $patient): View|string
    {
        $appointmentsQuery = Appointment::companyOnly()->with('appointmentType', 'patient', 'appointmentStatus');
        if (isset($patient)) {
            $appointmentsQuery->where('patient_id', $patient->id);
        }
        if ($request->filled('date')) {
            $appointmentsQuery->where('appointment_date', $request->date);
        }

        $appointments = $appointmentsQuery->get();
        if ($request->ajax()) {
            return view('patient_admin.appointment.list', compact('patient', 'appointments'))->render();
        }
        return view('patient_admin.appointment.index', compact('patient', 'appointments'));
    }
}
