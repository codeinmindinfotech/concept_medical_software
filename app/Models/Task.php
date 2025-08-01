<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'patient_id',
        'task_creator_id',
        'task_owner_id',
        'category_id',
        'subject',
        'task',
        'priority',
        'status_id',
        'start_date',
        'end_date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'task_creator_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'task_owner_id');
    }

    public function category()
    {
        return $this->belongsTo(DropDownValue::class, 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(DropDownValue::class, 'status_id');
    }

    public function followups()
    {
        return $this->hasMany(TaskFollowup::class);
    }
}
