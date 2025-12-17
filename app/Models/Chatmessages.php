<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chatmessages extends Model
{
    protected $table = 'chat_messages'; // explicitly set table
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'sender_type',
        'message'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        // if ($this->sender_type === 'user') {
        //     return $this->belongsTo(\App\Models\User::class, 'sender_id');
        // } elseif ($this->sender_type === 'patient') {
        //     return $this->belongsTo(\App\Models\Patient::class, 'sender_id');
        // }
        // return null;
        return $this->morphTo();
    }



}
