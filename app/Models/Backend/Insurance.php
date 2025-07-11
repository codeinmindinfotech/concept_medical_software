<?php

namespace App\Models\Backend;

use App\Models\ChargeCode;
use App\Models\ChargeCodePrice;
use App\Models\consultant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'code',
        'address',
        'contact_name',
        'contact',
        'email',
        'postcode',
        'fax',
    ];

    public function consultants()
    {
        return $this->belongsToMany(consultant::class)
                    ->withTimestamps();
    }

    public function chargePrices()
    {
        return $this->hasMany(ChargeCodePrice::class);
    }
    
    // In ChargeCodePrice model
    public function chargeCode() {
        return $this->belongsTo(ChargeCode::class, 'charge_code_id');
    }

}

