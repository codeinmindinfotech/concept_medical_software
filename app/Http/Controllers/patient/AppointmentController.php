<?php

namespace App\Http\Controllers\patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ChargeCode;
use App\Models\Clinic;
use App\Models\Patient;
use App\Traits\DropdownTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    use DropdownTrait;
    public function index(Request $request, Patient $patient)
    {
        $type = $request->get('type', 'upcoming'); // upcoming | completed
        // First load → full layout
        $user = auth()->user();
        if ((getCurrentGuard() == 'patient')) {
            $patients = Patient::companyOnly()->where('id', $user->id)->get();
        } else {
            $patients = Patient::companyOnly()->latest()->get();
        }
        $clinics = Clinic::companyOnly()->orderBy('planner_seq', 'asc')->get();
        $appointmentTypes = $this->getDropdownOptions('APPOINTMENT_TYPE');
        $diary_status = $this->getDropdownOptions('DIARY_CATEGORIES');
        $procedures = ChargeCode::companyOnly()->get();

        $query = Appointment::companyOnly()
            ->with('appointmentType', 'patient', 'appointmentStatus')
            ->where('patient_id', $patient->id);

        if ($type === 'upcoming') {
            $query->whereDate('appointment_date', '>=', now());
        }

        if ($type === 'completed') {
            $query->whereDate('appointment_date', '<', now());
        }

        // load more pagination
        $appointments = $query
            ->orderBy('appointment_date', 'asc')
            ->simplePaginate(6)
            ->appends(['type' => $type]);   // ← FIXED

            // Count upcoming
    $upcomingCount = Appointment::companyOnly()
    ->where('patient_id', $patient->id)
    ->whereDate('appointment_date', '>=', now())
    ->count();

// Count completed
$completedCount = Appointment::companyOnly()
    ->where('patient_id', $patient->id)
    ->whereDate('appointment_date', '<', now())
    ->count();
        // If AJAX request → return only the appointment cards
        if ($request->ajax()) {
            return view('patient_admin.appointment.partials.cards', compact('appointments'))->render();
        }


        return view('patient_admin.appointment.index', compact('patient', 'appointments', 'type', 'procedures', 'patients', 'diary_status', 'clinics', 'appointmentTypes','upcomingCount','completedCount'));
    }
}
