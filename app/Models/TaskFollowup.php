<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskFollowup extends Model
{
    protected $fillable = [
        'company_id',
        'task_id',
        'note',
        'followup_date',
        'created_by',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
