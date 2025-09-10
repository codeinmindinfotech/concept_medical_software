<?php
namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantService
{
    public static function setConnection($companyName)
    {
        $company = Company::where('name', $companyName)->first();
        if (!$company) {
            throw new \Exception("Company not found");
        }
    }
}
