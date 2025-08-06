<?php
namespace App\Models;

use App\Models\Backend\Insurance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Consultant extends Model
{
    use HasFactory;
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
