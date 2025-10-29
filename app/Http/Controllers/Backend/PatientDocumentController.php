<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientDocument;
use Illuminate\Http\Request;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;
use PhpOffice\PhpWord\TemplateProcessor;


class PatientDocumentController extends Controller
{
    public function index(Patient $patient)
    {
        $documents = $patient->documents()->with('template')->get();
        return view('patients.documents.index', compact('documents', 'patient'));
    }

    public function create(Patient $patient)
    {
        $templates = DocumentTemplate::all();
        return view('patients.documents.create', compact('patient', 'templates'));
    }

    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'document_template_id' => 'required|exists:document_templates,id',
        ]);
    
        $template = DocumentTemplate::findOrFail($request->document_template_id);
        $templatePath = storage_path('app/public/' . $template->file_path);
    
        // Prepare new file path
        $newFileName = uniqid('patient_doc_') . '.docx';
        $newStoragePath = "patient_docs/{$newFileName}";
        $newFullPath = storage_path("app/public/{$newStoragePath}");
    
        // ✅ Ensure destination folder exists
        $directoryPath = storage_path('app/public/patient_docs');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0775, true);
        }
    
        // ✅ Copy template to new file
        if (!copy($templatePath, $newFullPath)) {
            return back()->with('error', 'Could not copy template file.');
        }
    
        $replacements = [
            'Consultant.Name' => $patient->consultant->name,
            'Consultant.Description' => $patient->consultant->imc_no,
            'Consultant.Address1' => $patient->consultant->address,
            'Consultant.Address2' => $patient->consultant->address,
            'Consultant.Address3' => $patient->consultant->address,
            'Consultant.Address4' => $patient->consultant->address,
            'Consultant.PhoneNo' => $patient->consultant->phone,
            'Consultant.FaxNo' => $patient->consultant->fax,
            
            'General.CurrentDate' => now()->format('d/m/Y'),
        
            'Patient.Salutation' => $patient->title->value,
            'Patient.FirstName' => $patient->first_name,
            'Patient.Surname' => $patient->surname,
            'Patient.DOB' => $patient->dob->format('d/m/Y'),
            'Patient.Address1' => $patient->address,
            'Patient.Address2' => $patient->address,
            'Patient.Address3' => $patient->address,
            'Patient.Address4' => $patient->address,
            'Patient.Address5' => $patient->address,
        ];

        
        // 🔁 Replace placeholders
        $this->replaceDocxPlaceholders($newFullPath, $replacements);

    
        // 💾 Save in database
        PatientDocument::create([
            'patient_id' => $patient->id,
            'document_template_id' => $template->id,
            'file_path' => $newStoragePath,
        ]);
    
        return redirect()->route('patient-documents.index', $patient)
            ->with('success', 'Document created successfully.');
    }


    public function edit(Patient $patient, $documentId)
    {
        $templates = DocumentTemplate::all();
        $document = PatientDocument::where('id', $documentId)
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        abort_if($document->patient_id !== $patient->id, 403);

        $filePath = $document->file_path;
        $fileUrl = secure_asset('storage/' . $filePath);
        // $key = 'test-document-key-123';

        $key = generateDocumentKey($document);
        $token = $this->createJwtToken($document, $key, $fileUrl, $patient);
        $config = [
            'document' => [
                'storagePath' => storage_path('app/public'),
                'fileType' => 'docx',
                'key' => $key, // MUST be set
                'title' => $document->title ?? 'Document',
                'url' => $fileUrl, // full HTTPS URL
            ],
            'documentType' => 'word',
            'editorConfig' => [
                'mode' => 'edit',
                'callbackUrl' => url("/api/onlyoffice/callback/{$document->id}"),
                'user' => [
                    'id' => (string) $patient->id ?? '1',
                    'name' => $patient->full_name ?? 'Guest',
                ],
                'customization' => [
                    'forcesave' => true,
                ],
            ],
            'token' => $token, // your JWT token
        ];
        \Log::info('ONLYOFFICE key: ' . $key);
        \Log::info('ONLYOFFICE TOKEN: ' . $token);

        return view('patients.documents.edit', compact('patient', 'document', 'templates', 'config', 'token'));
    }

    public function update(Request $request, Patient $patient, PatientDocument $document)
    {
        // OnlyOffice should handle the save via callback
        return back()->with('info', 'Documents are auto-saved via OnlyOffice.');
    }

    public function destroy(Patient $patient, PatientDocument $document)
    {
        abort_if($document->patient_id !== $patient->id, 403);

        Storage::delete("public/" . $document->file_path);
        $document->delete();

        return redirect()->route('patient-documents.index', $patient)
            ->with('success', 'Document deleted.');
    }

    protected function replaceDocxPlaceholders($filePath, array $replacements)
    {
         // Load the template
        $template = new TemplateProcessor($filePath);

        // Replace each placeholder
        foreach ($replacements as $key => $value) {
            $template->setValue($key, $value);
        }

        // Save the updated file
        $template->saveAs($filePath);
    }

    private function createJwtToken($document, $key, $url, $patient)
    {
        $payload = [
            "document" => [
                "fileType" => "docx",
                "key" => $key,
                "title" => $document->title ?? 'Document',
                "url" => $url,
            ],
            "editorConfig" => [
                "callbackUrl" => url("/api/onlyoffice/callback/{$document->id}"),
                "mode" => "edit",
                "user" => [
                    'id' => (string) $patient->id ?? '1',
                    'name' => $patient->full_name ?? 'Guest',
                ],
            ],
            "iat" => time(),
            "exp" => time() + 3600,
        ];
        
        return JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
    }

}

