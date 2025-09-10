<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use Illuminate\Database\Eloquent\Model;

class Recall extends Model
{
    use BelongsToCompany;
    protected $fillable = ['company_id', 'patient_id', 'recall_interval', 'recall_date', 'status_id', 'note'];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function status() {
        return $this->belongsTo(DropDownValue::class, 'status_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
