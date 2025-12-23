<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;

class Doctor extends Model
{
    use BelongsToCompany, HasFactory, Notifiable;
  
    protected $guard_name = 'doctor'; 

    protected $fillable = [
        'company_id',
        'doctor_picture',
        'doctor_signature',
        'password',
        'name',
        'last_name',
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
    public function salutationOption()
    {
        return $this->belongsTo(DropDownValue::class, 'salutation');
    }
    public function getSalutationValueAttribute(): ?string
    {
        return $this->salutationOption?->value;
    }
    public function getFullNameAttribute(): string
    {
        return trim(
            ($this->salutation_value ? $this->salutation_value . ' ' : '') .
            $this->name . ' ' .
            $this->last_name
        );
    }

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
