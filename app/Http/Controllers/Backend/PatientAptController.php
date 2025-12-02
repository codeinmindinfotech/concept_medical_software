<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ChargeCode;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\PatientNote;
use App\Traits\DropdownTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class PatientAptController extends Controller
{
    use DropdownTrait;

    public function index(Request $request, Patient $patient): View|string
    {
        if (!$patient) {
            $patient = null;
        }

        $user = current_user();
        if (has_role('patient')) {
            $user = auth()->user();
            $patients = Patient::companyOnly()->with('title')->where('id', $user->id)->paginate(1);
        } else {
            $patients = Patient::companyOnly()->with(['title', 'preferredContact'])->get();
        }
        
        $clinics = Clinic::companyOnly()->get()->map(function ($clinic) {
            return $clinic;
        });

        $isSuperAdmin = $user->hasRole('superadmin');

        $appointmentTypes = $this->getDropdownOptions('APPOINTMENT_TYPE');
        $diary_status = $this->getDropdownOptions('DIARY_CATEGORIES');
        $procedures = ChargeCode::companyOnly()->get();
        
        $query = Appointment::with('clinic','patient','appointmentType','appointmentStatus','procedure');
                if (! $isSuperAdmin) {
                    $query->where('appointments.company_id', current_company_id());
                }
            $query->orderBy('appointment_date')
                ->where('appointments.patient_id',$patient->id)
                ->orderBy('appointment_date');
            $apts = $query->get();

        if (request()->ajax()) {
            return view('patients.apt.list', compact('diary_status','appointmentTypes','patient', 'apts','patients','clinics','procedures'));
        }
        
        return view(guard_view('patients.apt.index', 'patient_admin.apt.index'), compact('diary_status','appointmentTypes','patient', 'apts','patients','clinics','procedures'));  
    }
}
