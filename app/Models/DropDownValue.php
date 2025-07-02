<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropDownValue extends Model
{
    protected $fillable = ['drop_down_id', 'value'];

    public function dropDown()
    {
        return $this->belongsTo(DropDown::class);
    }
}
