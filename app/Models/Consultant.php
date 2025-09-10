<?php
namespace App\Models;
use App\Traits\BelongsToCompany;

use App\Models\Backend\Insurance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Consultant extends Model
{
    use BelongsToCompany, HasFactory;
    protected $fillable = [
        'company_id',
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
