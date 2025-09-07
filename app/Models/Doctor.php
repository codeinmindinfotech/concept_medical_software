<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Authenticatable
{
    use HasFactory;
    protected $guard_name = 'doctor'; 
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'name',
        'company',
        'salutation',
        'address',
        'postcode',
        'mobile',
        'phone',
        'fax',
        'email',
        'password',
        'contact',
        'contact_type_id',
        'payment_method_id',
        'note'
    ];  
    
    // Make sure password is hidden when serialized
    protected $hidden = [
        'password',
        'remember_token',
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

    public function getConnectionName()
    {
        return session('company_db_connection', 'mysql');
    }
}
