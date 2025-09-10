<?php

namespace App\Models;

use App\Models\Backend\Insurance;
use Illuminate\Database\Eloquent\Model;

class ChargeCodePrice extends Model
{
    protected $table = 'charge_code_prices';
    
    protected $fillable = [
        'company_id',
        'price',
        'charge_code_id',
        'insurance_id',
    ];

    public function chargeCode()
    {
        return $this->belongsTo(ChargeCode::class);
    }

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
