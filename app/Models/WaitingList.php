<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaitingList extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'clinic_id','visit_date', 'consult_note', 'patient_id', 'category_id'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function category()
    {
        return $this->belongsTo(DropdownValue::class, 'category_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}

