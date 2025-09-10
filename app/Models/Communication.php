<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    use BelongsToCompany;
    protected $fillable = [
        'company_id',
        'patient_id',
        'message',
        'method',
        'received',
    ];
    
    protected $casts = [
        'received' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
