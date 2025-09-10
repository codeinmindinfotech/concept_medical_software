<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPhysical extends Model
{
    protected $fillable = ['company_id', 'patient_id', 'physical_notes'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
