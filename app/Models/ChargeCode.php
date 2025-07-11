<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeCode extends Model
{
    protected $fillable = [
        'chargeGroupType',
        'code',
        'description',
        'price',
        'vatcodeid',
        'vatrate',
        'last_price_updated',
        'previous_amount',
    ];

    public function insurancePrices()
    {
        return $this->hasMany(ChargeCodePrice::class);
    }

    public function chargeGroup()
    {
        return $this->belongsTo(DropDownValue::class, 'chargeGroupType');
    }

    public function prices()
    {
        return $this->hasMany(ChargeCodePrice::class, 'charge_code_id');
    }
}
