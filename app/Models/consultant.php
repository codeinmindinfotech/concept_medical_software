<?php

namespace App\Models;

use App\Models\Backend\Insurance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consultant extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'fax',
        'email',
        'imc_no',
        'image',
    ];

    public function insurances()
    {
        return $this->belongsToMany(Insurance::class)
                    ->withTimestamps();
    }
}
