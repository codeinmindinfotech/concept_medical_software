<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'name',
        'company',
        'salutation',
        'address',
        'postcode',
        'mobile',
        'phone',
        'fax',
        'email',
        'contact',
        'contact_type_id',
        'payment_method_id',
        'note'
    ];  
    
    public function contactType()
    {
        return $this->belongsTo(DropDownValue::class,'contact_type_id');
    }
    
    public function paymentMethod()
    {
        return $this->belongsTo(DropDownValue::class, 'payment_method_id');
    }
}
