<?php

namespace App\Auth;

use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomPasswordBrokerManager extends PasswordBrokerManager
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

    protected function createTokenRepository(array $config)
    {
        $key = $this->app['config']['app.key'];

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        return tap(new \App\Auth\CustomDatabaseTokenRepository(
            DB::connection(),                 // ✅ Correct: connection
            $this->app['hash'],              // ✅ Hash manager
            $config['table'],
            $key,
            $config['expire'],
            $config['throttle'] ?? 0
        ), function ($repository) {
            $repository->setCompanyId($this->companyId); 
            $repository->setType($this->type);// set company ID here
        });
    }

}
