<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use BelongsToCompany;
    protected $fillable = [
        'company_id',
        'patient_id',
        'clinic_id',
        'appointment_type',
        'appointment_date',
        'start_time',
        'end_time',
        'apt_slots',
        'patient_need',
        'appointment_note',
        'arrival_time',
        'appointment_status',
        'admission_date',
        'admission_time',
        'procedure_id',
        'operation_duration',
        'ward',
        'allergy'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointmentType()
    {
        return $this->belongsTo(DropDownValue::class, 'appointment_type');
    }

    public function appointmentStatus()
    {
        return $this->belongsTo(DropDownValue::class, 'appointment_status');
    }

    public function procedure()
    {
        return $this->belongsTo(ChargeCode::class, 'procedure_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}