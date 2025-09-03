<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SwitchCompanyDatabase
{
    public function handle($request, Closure $next, $guard = null)
    {
         $companyName = session('company_name');

        if ($companyName) {
            $company = Company::where('name', $companyName)->first(); // use code instead of ID
            if ($company) {
                switchToCompanyDatabase($company);
            }
        }

        // Log the user and guard info for debugging
        \Log::info('Middleware user check:', [
            'guard' => $guard ?? 'default',
            'user' => $company ? $company->toArray() : null,
        ]);
        return $next($request);
    }
}
