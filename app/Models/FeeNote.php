<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeNote extends Model
{
    protected $fillable = [
        'patient_id',
        'clinic_id',
        'consultant_id',
        'chargecode_id',
        'comment',
        'narrative',
        'admission_date',
        'discharge_date',
        'procedure_date',
        'qty',
        'charge_gross',
        'reduction_percent',
        'charge_net',
        'vat_rate_percent',
        'line_total'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function chargecode()
    {
        return $this->belongsTo(chargecode::class);
    }
    

}
