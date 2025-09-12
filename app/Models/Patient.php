<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use App\Models\Backend\Insurance;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable
{
    use BelongsToCompany, HasFactory, HasRoles, Notifiable;
    protected $guard_name = 'patient';
    protected $fillable = [
        'company_id',
        'password',
        'consultant_id',
        'title_id',
        'patient_picture',
        'first_name',
        'surname',
        'dob',
        'gender',
        'doctor_id',
        'referral_doctor_id',
        'other_doctor_id',
        'solicitor_doctor_id',
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
        'covid_19_vaccination_date',
        'covid_19_vaccination_note',
        'fully_covid_19_vaccinated'
    ];

    protected $casts = [
        'covid_19_vaccination_date' => 'date',
        'rip_date' => 'date',
        'dob' => 'date'
    ];

    protected $with = ['title'];

    public function consultant() {
        return $this->belongsTo(Consultant::class);
    }
    
    public function title()
    {
        return $this->belongsTo(DropDownValue::class, 'title_id');
    }
    
    public function preferredContact()
    {
        return $this->belongsTo(DropDownValue::class, 'preferred_contact_id');
    }
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function referralDoctor()
    { 
        return $this->belongsTo(Doctor::class, 'referral_doctor_id'); 
    }

    public function otherDoctor()
    {
        return $this->belongsTo(Doctor::class, 'other_doctor_id'); 
    }
    
    public function solicitorDoctor()
    {
        return $this->belongsTo(Doctor::class, 'solicitor_doctor_id'); 
    }
    
    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    public function notes():HasMany
    {
        return $this->hasMany(PatientNote::class);
    }

    public function physicalNotes()
    {
        return $this->hasMany(PatientPhysical::class)->latest();
    }

    public function histories()
    {
        return $this->hasMany(PatientHistory::class);
    }

    public function WaitingLists()
    {
        return $this->hasMany(WaitingList::class);
    }

    public function FeeNoteList()
    {
        return $this->hasMany(FeeNote::class);
    }

    public function audio():HasMany
    {
        return $this->hasMany(PatientAudioFile::class);
    }

    public function recall()
    {
        return $this->hasMany(Recall::class);
    }

    public function communication()
    {
        return $this->hasMany(Communication::class);
    }

    public function getTitleValueAttribute(): ?string
    {
        return optional($this->title)->value;
    }

    public function getFullNameAttribute()
    {
        $title = $this->title_value;
        return ($title ? $title . ' ' : '') . "{$this->first_name} {$this->surname}";
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ClinicResetPasswordNotification($token, $this->company_id, $this->guard_name));
    }

}
