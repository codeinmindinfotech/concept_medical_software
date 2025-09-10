<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Doctor extends Authenticatable
{
    use HasFactory, HasRoles;
  
    protected $guard_name = 'doctor'; 

    protected $fillable = [
        'company_id',
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
}
