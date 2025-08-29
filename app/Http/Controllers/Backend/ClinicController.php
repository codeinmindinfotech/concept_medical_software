<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicRequest;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
class ClinicController extends Controller
{
    public function index(Request $request): View|string
    {
        $pageTitle = "Clinics List";
        $clinics = Clinic::latest()->get();

        if ($request->ajax()) {
            return view('clinics.list', compact('clinics'))->render();
        }

        return view('clinics.index', compact('clinics', 'pageTitle'));
    }

    public function create(): View
    {
        $pageTitle = "Create Clinic";
        return view('clinics.create', compact('pageTitle'));
    }

    public function store(ClinicRequest $request): JsonResponse
    {
        $isSuperadmin = AuthHelper::isRole('superadmin');

        $data = $this->extractDayFields($request);

        if($isSuperadmin) {
            // Step 1: Create a unique DB name for the clinic
            $dbName = 'concept_' . strtolower(preg_replace('/\s+/', '_', $data['code']));

            // Optional: change these to dynamic values or pull from config
            $dbUser = 'root';
            $dbPass = '';
            $dbHost = '127.0.0.1';
            $dbPort = '3306';

            // Step 2: Create the clinic database
            $existing = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

            if (empty($existing)) {
                DB::statement("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            } 
            // else {
            //     // Optional: throw error or log warning
            //     throw new \Exception("Database '$dbName' already exists.");
            // }
            
            // Step 3: Save the clinic in the main database
            $data['db_host'] = $dbHost;
            $data['db_port'] = $dbPort;
            $data['db_database'] = $dbName;
            $data['db_username'] = $dbUser;
            $data['db_password'] = $dbPass;
            $data['password'] = Hash::make($data['code']); // Hash password if included

            $clinic = Clinic::create($data);

            // Step 4: Set up clinic DB connection dynamically
            Config::set("database.connections.clinic", [
                'driver' => 'mysql',
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $dbPass,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            DB::purge('clinic');
            DB::reconnect('clinic');

            // Step 1: Run ALL migrations using the 'clinic' database connection
            Artisan::call('migrate', [
                '--database' =>'clinic', // your dynamic or tenant connection
                '--force' => true,        // run without confirmation
            ]);

            // Step 2: Run a specific seeder on the same 'clinic' database
            Artisan::call('db:seed', [
                '--database' => 'clinic',
                '--class' => 'ClinicSeeder',
                '--force' => true,
            ]);

            // Step 6: Optionally insert clinic data into the clinic DB
            DB::connection('clinic')->table('clinics')->insert($data);

        } else {
            $clinic = Clinic::create($data);
        }

        
        return response()->json([
            'redirect' => guard_route('clinics.index'),
            'message' => 'Clinic created successfully, and clinic database initialized.',
        ]);
    }

    public function edit($clinicId): View
    {
        $clinic = Clinic::findOrFail($clinicId);
        $pageTitle = "Edit Clinic";
        return view('clinics.edit', compact('clinic', 'pageTitle'));
    }

    public function update(ClinicRequest $request, $clinicId): JsonResponse
    {
        $data = $this->extractDayFields($request);
        $clinic = Clinic::findOrFail($clinicId);
        $clinic->update($data);

        return response()->json([
            'redirect' => guard_route('clinics.index'),
            'message' => 'Clinic updated successfully',
        ]);
    }

    public function show($clinicId): View
    {
        $pageTitle = "Show Clinic";
        return view('clinics.show', compact('clinic', 'pageTitle'));
    }

    public function destroy($clinicId): RedirectResponse
    {
        $clinic = Clinic::findOrFail($clinicId);
        $clinic->delete();

        return redirect()->guard_route('clinics.index')->with('success', 'Clinic deleted successfully.');
    }

    protected function extractDayFields(Request $request): array
    {
        $data = $request->validated();
        $days = ['mon','tue','wed','thu','fri','sat','sun'];

        foreach ($days as $day) {
            $data[$day] = $request->boolean($day);
        }

        return $data;
    }
}
