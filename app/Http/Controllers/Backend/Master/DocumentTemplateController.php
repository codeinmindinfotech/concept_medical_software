<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Http\Request;

class DocumentTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = DocumentTemplate::all();
        return view('documents.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:letter,form',
            'file' => 'required|file|mimes:doc,docx,pdf|max:2048',
        ]);
        $filePath = $request->file('file')->store('document_templates', 'public');

        DocumentTemplate::create([
            'name' => $request->name,
            'type' => $request->type,
            'file_path' => $filePath,
            'company_id' => auth()->user()->company_id ?? null,
        ]);

        return redirect()->route('documents.index')->with('success', 'Template created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $template = DocumentTemplate::findOrFail($id);
    
        $data = [
            'consultant_name' => 'DR Consultant Name',
            'hospital_address' => "Egmont Kanturk County Cork",
            'phone_number' => '01869889',
            'email' => 'test@test.com',
            'date' => now()->format('d/m/Y'),
            'doctor_name' => 'Marion Smith',
            'doctor_address' => "Egmont\nKanturk\nCounty Cork",
            'doctor_email' => 'test@test.com',
            'letter_number' => '20',
            'patient_name' => 'Niru Patel',
            'patient_address' => 'Add1 Add2 Add3 Add4',
            'patient_dob' => '01/01/1960',
            'patient_phone' => '01869889',
            'patient_mobile' => '0879192956',
            'brc_number' => '123456',
            'patient_age' => '65 Years 8 Mths',
            'doctor_first_name' => 'Marion',
            'letter_body' => 'This is a sample letter body content.',
    
            // Add missing variables used in the template
            'sex_male' => true,
            'sex_female' => false,
            'diet_normal' => true,
            'diet_diabetic' => false,
            'diet_coeliac' => false,
            'diet_other' => false,
            'diet_fasting' => true,
            'diet_fluids' => false,
            'diet_normal_pre' => false,
            'main_theatre' => true,
            'extern_room' => false,
            'attachment_consent' => true,
            'attachment_history' => false,
            'attachment_investigations' => true,
            'insurance_verified' => true,
            'signature_date' => now()->format('d/m/Y'),
            'procedure_date' => '20/06/2025',
            'procedure_time' => '12:00',
            'procedure_code' => '683',
            'clinical_details' => 'Example diagnosis and reason for admission.',
            'mobility_mobile_with' => true,
            'mobility_wheelchair' => false,
            'mobility_other' => '',
            'insurance_name' => 'VHI',
            'insurance_policy_number' => '34342',
            'insurance_plan' => 'Plan B',
            'patient_title' => 'Ms',
            'patient_surname' => 'Patel',
            'patient_first_name' => 'Niru',
            'patient_sex' => 'Female',
            'patient_occupation' => 'Retired',
            'patient_email' => 'test@test.com',
            'patient_phone_home' => '01869889',
            'patient_phone_work' => '018 689689678',
            'nok_name' => 'Mrs Test',
            'nok_relationship' => 'Wife',
            'nok_telephone' => '08763622939',
            'nok_address' => 'Next of Kin Address Line',
            'diet_normal' => true,
            'diet_diabetic' => false,
            'diet_coeliac' => false,
            'diet_other' => false,
        ];
    
    
        $rendered = Blade::render($template->template_body, $data);
    
        return view('documents.show', compact('template', 'rendered'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = DocumentTemplate::findOrFail($id);
        
        return view('documents.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentTemplate $document)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:letter,form',
            'file' => 'nullable|file|mimes:doc,docx,pdf|max:2048',
        ]);

        $data = $request->only('name', 'type');

        if ($request->hasFile('file')) {
            // Optionally delete old file
            if ($document->file_path && \Storage::disk('public')->exists($document->file_path)) {
                \Storage::disk('public')->delete($document->file_path);
            }

            $data['file_path'] = $request->file('file')->store('document_templates', 'public');
        }

        $document->update($data);

        return redirect()->route('documents.index')->with('success', 'Template updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DocumentTemplate::destroy($id);
        return redirect()->route('documents.index')->with('success', 'Template deleted');
    }
}
