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
use Illuminate\Support\Facades\App;


class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view(guard_view('companies.index', 'patient_admin.company.index'), compact('companies'));
    }

    public function create()
    {
        return view(guard_view('companies.create', 'patient_admin.company.create'));
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
            'whatsapp_phone_number_id' => 'nullable|string|max:50',
            'whatsapp_business_account_id' => 'nullable|string|max:50',
            'whatsapp_access_token' => 'nullable|string',
            'webex_token' => 'nullable|string',
            'webex_sender' => 'nullable|string',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
        ]);
        

        try {
            $data = $request->all();
            $company = Company::create($data);           
           
            // 2. Create or find the admin user (and associate company)
            $user = User::firstOrCreate(
                [
                    'email' => $company->email,
                    'company_id' => $company->id
                ],
                [
                    'name' => $company->name,
                    'password' => Hash::make('123456')
                ]
            );
            
            
            setupCompanyRolesAndPermissions($company, $user);


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
    
        return view(guard_view('companies.show', 'patient_admin.company.show'),compact('company'));
    }

    public function edit($companyId)
    {
        $company = Company::findOrFail($companyId);
        return view(guard_view('companies.edit', 'patient_admin.company.edit'), compact('company'));
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
            'whatsapp_phone_number_id' => 'nullable|string|max:50',
            'whatsapp_business_account_id' => 'nullable|string|max:50',
            'whatsapp_access_token' => 'nullable|string',
            'webex_token' => 'nullable|string',
            'webex_sender' => 'nullable|string',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
        ]);

        $company = Company::findOrFail($id);
        $data = $request->all();

        // Prevent non-superadmins from changing name/email
        if (!has_role('superadmin')) {
            unset($data['name'], $data['email']);
        }

        // Now update the company safely
        $company->update($data);

        // When updating a company
        $user = User::where('email', $company->email)
            ->where('company_id', $company->id) // check company_id too
            ->first();        
        if (!$user) {
           // 2. Create or find the admin user (and associate company)
           $user = User::firstOrCreate(
                [
                    'email' => $company->email,
                    'company_id' => $company->id
                ],
                [
                    'name' => $company->name,
                    'password' => Hash::make('123456')
                ]
            );
            $recipients = globalNotificationRecipients();
            if (!empty($recipients) && filter_var($company->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($company->email)->cc($recipients)->send(new CompanyCreatedMail($company));
            } else {
                \Log::error('Invalid recipients or company email', [
                    'to' => $company->email,
                    'cc' => $recipients
                ]);
            }
        }
        setupCompanyRolesAndPermissions($company, $user);

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

            return redirect(guard_route('companies.index'))->with('success', 'Company deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to delete company: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to delete company: ' . $e->getMessage());
        }
    }

    public function getManagers(Company $company)
    {
        $managers = $company->users()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'manager');
            })
            ->select('id', 'name')
            ->get();

        return response()->json($managers);
    }

}