<?php

namespace App\Models;
use App\Traits\BelongsToCompany;
use App\Traits\HasSoftDeletedPatientScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientAudioFile extends Model
{
    use BelongsToCompany, HasSoftDeletedPatientScope;
    protected $fillable = [
        'company_id',
        'patient_id',
        'doctor_id',
        'file_path',
        'transcription'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
