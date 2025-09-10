<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use Illuminate\Database\Eloquent\Model;

class PatientPhysical extends Model
{
    use BelongsToCompany;
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
