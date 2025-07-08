<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    protected $fillable = ['patient_id', 'history_notes'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
