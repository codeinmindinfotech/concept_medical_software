<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Mail\CompanyCreatedMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

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
            'name' => [
                'required',
                'unique:companies,name',
                'regex:/^[A-Za-z0-9_]+$/',
            ],
            'email' => 'nullable|email|max:255|unique:companies,email',
        ]);
        

        try {
            $data = $request->all();
            $company = Company::create($data);           
           
            // 2. Create or find the admin user (and associate company)
            $user = User::firstOrCreate(
                ['email' => $company->email], // company_email
                [
                    'name' => $company->name,
                    'password' => Hash::make('123456'), // default password
                    'company_id' => $company->id // assumes user has company_id field
                ]
            );

            // 3. Create or find the "manager" role for the 'web' guard
            $role = Role::firstOrCreate(
                ['name' => 'manager', 'guard_name' => 'web']
            );

            // 4. Sync all 'web' permissions to the manager role
            $permissions = Permission::where('guard_name', 'web')->get();
            $role->syncPermissions($permissions);

            // 5. Assign role to user if not already assigned
            if (!$user->hasRole($role->name)) {
                $user->assignRole($role->name);
            }


            $recipients = globalNotificationRecipients();
            if (!empty($recipients) && filter_var($company->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($company->email)->cc($recipients)->send(new CompanyCreatedMail($company));
            } else {
                \Log::error('Invalid recipients or company email', [
                    'to' => $company->email,
                    'cc' => $recipients
                ]);
            }
            
            return response()->json([
                'redirect' =>guard_route('companies.index'),
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
        $data = $request->validate([
            'name' => [
                'required',
                'unique:companies,name,'. $id,
                'regex:/^[A-Za-z0-9_]+$/',
            ],
            'email' => 'nullable|email|max:255|unique:companies,email,'. $id,
        ]);

        $company = Company::findOrFail($id);
        $company->update($data);

        return response()->json([
            'redirect' =>guard_route('companies.index'),
            'message' => 'Company updated successfully',
        ]);
    }


    public function destroy($companyId): RedirectResponse
    {
        try {
            $company = Company::findOrFail($companyId);
            $company->delete();

            return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to delete company: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to delete company: ' . $e->getMessage());
        }
    }

}