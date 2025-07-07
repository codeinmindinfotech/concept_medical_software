<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientNote extends Model
{
    protected $fillable = [
        'patient_id',
        'method',
        'notes',
        'completed',
    ];
    
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
