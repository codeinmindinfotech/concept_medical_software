<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    protected $fillable = [
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
}
