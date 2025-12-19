<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name' ,
        'email',
        'whatsapp_phone_number_id',
        'whatsapp_business_account_id',
        'whatsapp_access_token',
        'webex_token',
        'webex_sender'
    ];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
