<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'name' => 'required|unique:companies,name',
            'db_database' => 'required|unique:companies,db_database',
        ]);

        try {
            $data = $request->all();

            // Step 1: Prepare DB config
            $dbName = 'concept_' . strtolower(preg_replace('/\s+/', '_', $data['db_database']));
            $dbHost = $data['db_host'] ?? '127.0.0.1';
            $dbPort = $data['db_port'] ?? '3306';
            $dbUser = $data['db_username'] ?? 'root';
            $dbPass = $data['db_password'] ?? '';

            // Step 2: Check if DB already exists
            $existing = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

            if (empty($existing)) {
                DB::statement("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }

            // Step 3: Store company in main DB
            $data['db_host'] = $dbHost;
            $data['db_port'] = $dbPort;
            $data['db_database'] = $dbName;
            $data['db_username'] = $dbUser;
            $data['db_password'] = $dbPass;

            $company = Company::create($data);

            // Step 4: Set up dynamic DB connection
            Config::set("database.connections.company", [
                'driver' => 'mysql',
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $dbPass,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            DB::purge('company');
            DB::reconnect('company');

            // Step 5: Run migrations and seeders
            Artisan::call('migrate', [
                '--database' => 'company',
                '--force' => true,
            ]);

            Artisan::call('db:seed', [
                '--database' => 'company',
                '--class' => 'CompanySeeder',
                '--force' => true,
            ]);

            // Step 6: Insert company record in the tenant DB
            DB::connection('company')->table('companies')->insert([
                'name' => $data['name'],
                'db_host' => $dbHost,
                'db_port' => $dbPort,
                'db_database' => $dbName,
                'db_username' => $dbUser,
                'db_password' => $dbPass,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'redirect' => guard_route('companies.index'),
                'message' => 'Company created successfully',
            ]);

        } catch (\Exception $e) {
            \Log::error('Company creation failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to create company: ' . $e->getMessage()
            ], 400);
        }
    }

    public function show($id): View
    {
        $company = Company::find($id);
    
        return view('companies.show',compact('company'));
    }

    public function edit($companyId)
    {
        $company = Company::findOrFail($companyId);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|unique:companies,name,' . $id,
            'db_database' => 'required|unique:companies,db_database,' . $id,
        ]);

        try {
            $company = Company::findOrFail($id);
            $data = $request->all();

            // Step 1: Prepare updated DB config
            $rawDbName = strtolower(preg_replace('/\s+/', '_', $data['db_database']));

            if (!str_starts_with($rawDbName, 'concept_')) {
                $newDbName = 'concept_' . $rawDbName;
            } else {
                $newDbName = $rawDbName;
            }
            $dbHost = $data['db_host'] ?? '127.0.0.1';
            $dbPort = $data['db_port'] ?? '3306';
            $dbUser = $data['db_username'] ?? 'root';
            $dbPass = $data['db_password'] ?? '';

            $oldDbName = $company->db_database;

            // Step 2: Check if DB name changed
            if ($newDbName !== $oldDbName) {
                // Check if new DB already exists
                \Log::error('changed: ' . $newDbName);

                $existing = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$newDbName]);

                if (empty($existing)) {
                    // Create new DB
                    DB::statement("CREATE DATABASE `$newDbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                    // Run migrations & seeders on new DB
                    Config::set("database.connections.company", [
                        'driver' => 'mysql',
                        'host' => $dbHost,
                        'port' => $dbPort,
                        'database' => $newDbName,
                        'username' => $dbUser,
                        'password' => $dbPass,
                        'charset' => 'utf8mb4',
                        'collation' => 'utf8mb4_unicode_ci',
                    ]);

                    DB::purge('company');
                    DB::reconnect('company');

                    Artisan::call('migrate', [
                        '--database' => 'company',
                        '--force' => true,
                    ]);

                    Artisan::call('db:seed', [
                        '--database' => 'company',
                        '--class' => 'CompanySeeder',
                        '--force' => true,
                    ]);
                }
            }

            // Step 3: Update main database
            $company->update([
                'name' => $data['name'],
                'db_host' => $dbHost,
                'db_port' => $dbPort,
                'db_database' => $newDbName,
                'db_username' => $dbUser,
                'db_password' => $dbPass,
            ]);

            // Step 4: Update or insert into tenant DB
            Config::set("database.connections.company", [
                'driver' => 'mysql',
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $newDbName,
                'username' => $dbUser,
                'password' => $dbPass,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            DB::purge('company');
            DB::reconnect('company');

            $tenantCompany = DB::connection('company')->table('companies')
                ->where('db_database', $newDbName)
                ->first();

            if ($tenantCompany) {
                DB::connection('company')->table('companies')
                    ->where('id', $tenantCompany->id)
                    ->update([
                        'name' => $data['name'],
                        'db_host' => $dbHost,
                        'db_port' => $dbPort,
                        'db_database' => $newDbName,
                        'db_username' => $dbUser,
                        'db_password' => $dbPass,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::connection('company')->table('companies')->insert([
                    'name' => $data['name'],
                    'db_host' => $dbHost,
                    'db_port' => $dbPort,
                    'db_database' => $newDbName,
                    'db_username' => $dbUser,
                    'db_password' => $dbPass,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'redirect' => guard_route('companies.index'),
                'message' => 'Company updated successfully',
            ]);

        } catch (\Exception $e) {
            \Log::error('Company update failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to update company: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function destroy($companyId): RedirectResponse
    {
        try {
            // Step 1: Find the company
            $company = Company::findOrFail($companyId);

            $dbName = $company->db_database;

            // Step 2: Drop the tenant's database (if it exists)
            $exists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

            if (!empty($exists)) {
                DB::statement("DROP DATABASE `$dbName`");
            }

            // Step 3: Delete the company from the main DB
            $company->delete();

            return redirect()->guard_route('companies.index')->with('success', 'Company and its database deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to delete company: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to delete company: ' . $e->getMessage());
        }
    }

}
