<?php

namespace App\Models;
use App\Traits\BelongsToCompany;
use App\Traits\HasSoftDeletedPatientScope;
use Illuminate\Database\Eloquent\Model;

class Recall extends Model
{
    use BelongsToCompany, HasSoftDeletedPatientScope;
    protected $fillable = ['company_id', 'patient_id', 'recall_interval', 'recall_date', 'status_id', 'note'];

    public function patient() {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function status() {
        return $this->belongsTo(DropDownValue::class, 'status_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
