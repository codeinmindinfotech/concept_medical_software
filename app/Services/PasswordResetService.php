<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function sendResetLink($user, string $type, string $brokerName)
    {
        $status = Password::RESET_LINK_SENT;
        if (App::environment('local')) {
            $brokerManager = app('auth.password');
            $brokerManager->setCompanyId($user->company_id ?? null);
            $brokerManager->setType($type);

            $broker = $brokerManager->broker($brokerName);

            $status = $broker->sendResetLink(['email' => $user->email]);

            if ($status !== Password::RESET_LINK_SENT) {
                throw new \Exception("Failed to send reset link. Status: $status");
            }
        }
        return $status;
    }
}
