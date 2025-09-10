<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class ChargeCode extends Model
{
    use BelongsToCompany;
    protected $fillable = [
        'company_id',
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
