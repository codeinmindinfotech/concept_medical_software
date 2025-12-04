<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable
{
    use BelongsToCompany, HasFactory, HasRoles, Notifiable;
  
    protected $guard_name = 'doctor'; 

    protected $fillable = [
        'company_id',
        'doctor_picture',
        'doctor_signature',
        'password',
        'name',
        'company',
        'salutation',
        'address',
        'postcode',
        'mobile',
        'phone',
        'fax',
        'email',
        'contact',
        'contact_type_id',
        'payment_method_id',
        'note'
    ];  
    
    public function contactType()
    {
        return $this->belongsTo(DropDownValue::class,'contact_type_id');
    }
    
    public function paymentMethod()
    {
        return $this->belongsTo(DropDownValue::class, 'payment_method_id');
    }

    public function audios():HasMany
    {
        return $this->hasMany(PatientAudioFile::class);
    }

    public function patient()
    {
        return $this->hasMany(Patient::class);
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
