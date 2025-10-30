<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;    

class Message extends Model
{

    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'company_id',
        'appointment_id',
        'to',
        'direction',
        'type',
        'content',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}

