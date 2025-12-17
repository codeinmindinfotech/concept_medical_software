<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    
    protected $fillable = ['title','company_id','created_by_id','created_by_type'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Chatmessages::class);//,'conversation_id', 'id');
    }

    public function participants()
    {
        return $this->hasMany(ConversationParticipant::class, 'conversation_id', 'id');
    }

    // Scope by company
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

}