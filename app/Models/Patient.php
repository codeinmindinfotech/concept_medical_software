<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'name',
        'dob',
        'gender',
        'phone',
        'email',
        'address',
        'emergency_contact',
        'medical_history',
        'insurance',
    ];
}
