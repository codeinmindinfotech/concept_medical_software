<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;

class SetCompanyMailConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->company) {
            $company = auth()->user()->company;

            Config::set('mail.mailers.smtp.host', $company->mail_host);
            Config::set('mail.mailers.smtp.port', $company->mail_port);
            Config::set('mail.mailers.smtp.username', $company->mail_username);
            Config::set('mail.mailers.smtp.password', $company->mail_password);
            Config::set('mail.mailers.smtp.encryption', $company->mail_encryption);

            Config::set('mail.from.address', $company->mail_from_address);
            Config::set('mail.from.name', $company->mail_from_name);
        }

        return $next($request);
    }
}