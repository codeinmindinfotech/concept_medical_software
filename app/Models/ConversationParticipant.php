<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationParticipant extends Model
{
    protected $fillable = ['conversation_id', 'participant_id', 'participant_type', 'company_id'];

    // public function participant()
    // {
    //     return $this->morphTo();
    // }
    public function participant()
    {
        if ($this->participant_type === 'user') {
            return $this->belongsTo(\App\Models\User::class, 'participant_id');
        } elseif ($this->participant_type === 'patient') {
            return $this->belongsTo(\App\Models\Patient::class, 'participant_id');
        }
        return null;
    }
}
