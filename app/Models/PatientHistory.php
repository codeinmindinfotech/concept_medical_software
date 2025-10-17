<?php

namespace App\Models;
use App\Traits\BelongsToCompany;
use App\Traits\HasSoftDeletedPatientScope;
use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    use BelongsToCompany,HasSoftDeletedPatientScope;
    protected $fillable = ['company_id','patient_id', 'history_notes'];

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
