<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    protected $casts = [
        'clinic_type' => 'string',
    ];
}
