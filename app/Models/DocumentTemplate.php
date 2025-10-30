<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use BelongsToCompany;
    
    protected $fillable = ['name', 'type', 'file_path', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

