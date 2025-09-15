<?php

namespace App\Auth;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Support\Facades\DB;

class CustomDatabaseTokenRepository extends DatabaseTokenRepository
{
    protected $companyId, $type;

    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }
    public function setType($type)
    {
        $this->type = $type;
    }

    public function create($user)
    {
        $email = $user->getEmailForPasswordReset();

        $this->deleteExisting($user);

        $token = $this->createNewToken();

        \Log::info('CustomDatabaseTokenRepository@create called', [
            'email' => $user->getEmailForPasswordReset(),
            'company_id' => $this->companyId,
            'type' => $this->type,
        ]);
        
        DB::table($this->table)->insert([
            'email' => $email,
            'company_id' => $this->companyId,
            'type' => $this->type,
            'token' => $this->hasher->make($token),
            'created_at' => now(),
        ]);

        return $token;
    }

    protected function deleteExisting($user)
    {
        DB::table($this->table)
            ->where('email', $user->getEmailForPasswordReset())
            ->where('company_id', $this->companyId)
            ->where('type', $this->type)
            ->delete();
    }

    public function exists($user, $token)
    {
        $record = (array) DB::table($this->table)
            ->where('email', $user->getEmailForPasswordReset())
            ->where('company_id', $this->companyId)
            ->where('type', $this->type)
            ->first();

        return $record &&
            !$this->tokenExpired($record['created_at']) &&
            $this->hasher->check($token, $record['token']);
    }

    public function delete($user)
    {
        DB::table($this->table)
            ->where('email', $user->getEmailForPasswordReset())
            ->where('company_id', $this->companyId)
            ->where('type', $this->type)
            ->delete();
    }
}