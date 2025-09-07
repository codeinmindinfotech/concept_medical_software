<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class SwitchCompanyDatabase
{
    public function handle($request, Closure $next)
    {
        
        
        \Log::info('Before switch', ['connection' => DB::connection()->getDatabaseName()]);

        // dd(11);
        \Log::info('SwitchCompanyDatabase middleware ran.');

        $tenantConfig = session('tenant_db_config');
        if ($tenantConfig) {
            
            Config::set('database.connections.tenant', $tenantConfig);

            Config::set('database.default', 'tenant');

            DB::purge('tenant');
            DB::reconnect('tenant');

            \Log::info('Tenant DB connection set in middleware.', ['tenant_db' => $tenantConfig['database']]);
        } else {
            Config::set('database.default', 'mysql');  // ✅ use 'mysql' not 'sql'
            DB::purge('mysql');
            DB::reconnect('mysql');
            \Log::warning('Tenant DB config missing in session');
        }

        return $next($request);
    }




}
