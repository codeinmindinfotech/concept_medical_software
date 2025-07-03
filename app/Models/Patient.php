<?php

namespace App\Models;

use App\Models\Backend\Insurance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'title_id',
        'first_name',
        'surname',
        'dob',
        'gender',
        'doctor_id',
        'phone',
        'email',
        'address',
        'emergency_contact',
        'medical_history',
        'insurance_id',
        'insurance_plan',
        'policy_no',
        'referral_reason',
        'symptoms',
        'patient_needs',
        'allergies',
        'diagnosis',
        'preferred_contact_id',
        'rip',
        'rip_date',
        'sms_consent',
        'email_consent',
    ];

    public function title()
    {
        return $this->belongsTo(DropDownValue::class);
    }
    public function preferredContact()
    {
        return $this->belongsTo(DropDownValue::class, 'preferred_contact_id');
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

}
