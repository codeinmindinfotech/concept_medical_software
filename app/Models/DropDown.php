<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropDown extends Model
{
    protected $fillable = ['name'];

    public function values()
    {
        return $this->hasMany(DropDownValue::class);
    }
}
