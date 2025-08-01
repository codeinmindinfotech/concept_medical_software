<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recall extends Model
{
    protected $fillable = ['patient_id', 'recall_interval', 'recall_date', 'status_id', 'note'];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function status() {
        return $this->belongsTo(DropDownValue::class, 'status_id');
    }

}
