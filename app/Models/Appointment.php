<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
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
        'appointment_status'
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
}