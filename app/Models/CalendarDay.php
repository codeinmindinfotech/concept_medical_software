<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarDay extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = ['clinic_id', 'date', 'company_id', 'is_active'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}

