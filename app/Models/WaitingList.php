<?php

namespace App\Models;
use App\Traits\BelongsToCompany;
use App\Traits\HasSoftDeletedPatientScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaitingList extends Model
{
    use BelongsToCompany, SoftDeletes, HasSoftDeletedPatientScope;

    protected $fillable = [
        'company_id','clinic_id','visit_date', 'consult_note', 'patient_id', 'category_id'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(DropdownValue::class, 'category_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

