<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name' ,
        'email',
        'document_password',
        'whatsapp_phone_number_id',
        'whatsapp_business_account_id',
        'whatsapp_access_token',
        'webex_token',
        'webex_sender',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name'
    ];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
