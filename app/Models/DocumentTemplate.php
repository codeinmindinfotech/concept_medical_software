<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $fillable = ['name', 'type', 'file_path', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

