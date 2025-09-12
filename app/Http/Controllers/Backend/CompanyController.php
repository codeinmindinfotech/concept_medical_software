<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Mail\CompanyCreatedMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

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
           
            $recipients = globalNotificationRecipients();
            Mail::to($recipients)->cc($recipients)->send(new CompanyCreatedMail($company));

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