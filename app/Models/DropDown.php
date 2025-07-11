<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropDown extends Model
{
    protected $fillable = ['name','code'];

    // Always store code as uppercase
    public function setCodeAttribute($value)
    {
        // Set only if it's being created
        if (! $this->exists) {
            $this->attributes['code'] = strtoupper($value);
        }
    }

    // Prevent editing code after creation
    protected static function booted(): void
    {
        static::updating(function ($dropdown) {
            if ($dropdown->isDirty('code')) {
                throw new \Exception('The code field cannot be modified after creation.');
            }
        });
    }
    
    public function values()
    {
        return $this->hasMany(DropDownValue::class);
    }
}
