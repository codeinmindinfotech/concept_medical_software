<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPhysical extends Model
{
    protected $fillable = ['patient_id', 'physical_notes'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
