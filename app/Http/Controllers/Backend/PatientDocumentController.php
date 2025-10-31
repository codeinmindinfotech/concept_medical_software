<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\KeywordHelper;
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
    
        // $replacements = [
        //     'Consultant.Name' => $patient->consultant->name,
        //     'Consultant.Description' => $patient->consultant->imc_no,
        //     'Consultant.Address1' => $patient->consultant->address,
        //     'Consultant.Address2' => $patient->consultant->address,
        //     'Consultant.Address3' => $patient->consultant->address,
        //     'Consultant.Address4' => $patient->consultant->address,
        //     'Consultant.PhoneNo' => $patient->consultant->phone,
        //     'Consultant.FaxNo' => $patient->consultant->fax,
            
        //     'General.CurrentDate' => now()->format('d/m/Y'),
        
        //     'Patient.Salutation' => $patient->title->value,
        //     'Patient.FirstName' => $patient->first_name,
        //     'Patient.Surname' => $patient->surname,
        //     'Patient.DOB' => $patient->dob->format('d/m/Y'),
        //     'Patient.Address1' => $patient->address,
        //     'Patient.Address2' => $patient->address,
        //     'Patient.Address3' => $patient->address,
        //     'Patient.Address4' => $patient->address,
        //     'Patient.Address5' => $patient->address,
        // ];

        
        // 🔁 Replace placeholders
        // $this->replaceDocxPlaceholders($newFullPath, $replacements);
        KeywordHelper::replaceKeywords($newFullPath, $patient);

    
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
        $fullPath = storage_path('app/public/' . $filePath);

        // $this->replaceDocxPlaceholders($fullPath, $replacements);
        KeywordHelper::replaceKeywords($fullPath, $patient);
        $fileUrl = secure_asset('storage/' . $filePath);
        // $key = 'test-document-key-123';

        \Log::info("Replaced DOCX saved at: {$fullPath}, size: " . filesize($fullPath));

        // // Optional: return the file for download to manually check
        // return response()->download($fullPath);

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

    // protected function replaceDocxPlaceholders($filePath, array $replacements)
    // {
    //     // Load the template
    //     $template = new TemplateProcessor($filePath);
    //     $tempPath = $filePath . '_temp.docx';

    //     // Replace each placeholder
    //     foreach ($replacements as $key => $value) {
    //         $template->setValue($key, $value);
    //         \Log::info("Replacing {$key} with {$value}");
    //     }

    //     // Save the updated file
    //     $template->saveAs($filePath);

    //     // Replace original
    //     unlink($filePath); // delete old file
    //     rename($tempPath, $filePath);
    //     \Log::info("DOCX saved: {$filePath}");

    // }

    // protected function preprocessSmartQuotes($filePath)
    // {
    //     $zip = new \ZipArchive;
    //     $tmp = $filePath . '_tmp.zip';
    
    //     copy($filePath, $tmp);
    
    //     if ($zip->open($tmp) === true) {
    //         $content = $zip->getFromName('word/document.xml');
    //         // Replace smart quotes with ${...} placeholders
    //         $content = preg_replace(['/«(.*?)»/', '/\[(.*?)\]/'], ['${$1}', '${$1}'], $content);
    //         $zip->addFromString('word/document.xml', $content);
    //         $zip->close();
    
    //         copy($tmp, $filePath);
    //         unlink($tmp);
    //     }
    // }
    // protected function replaceDocxPlaceholders($filePath, array $replacements)
    // {
    //     \Log::info("🔧 Starting replacement for: {$filePath}");

    //     // 🔁 Convert «... » → ${...}
    //     $this->preprocessSmartQuotes($filePath);

    //     $template = new TemplateProcessor($filePath);

    //     foreach ($replacements as $key => $value) {
    //         \Log::info("Replacing {$key} with: {$value}");
    //         $template->setValue($key, $value);
    //     }

    //     $template->saveAs($filePath);
    //     \Log::info("✅ Replacement completed and saved to: {$filePath}");
    // }

    // protected function replaceDocxPlaceholders($filePath, array $replacements)
    // {
    //     try {
    //         \Log::info("🔧 Starting replacement for: {$filePath}");
    
    //         // Temporary file (to avoid Windows file lock)
    //         $tempPath = $filePath . '_temp.docx';
    
    //         // Load the DOCX
    //         $template = new TemplateProcessor($filePath);
    
    //         // Replace placeholders
    //         foreach ($replacements as $key => $value) {
    //             $template->setValue($key, $value ?? '');
    //             \Log::info("Replacing {$key} with: {$value}");
    //         }
    
    //         // Save to temp file first
    //         $template->saveAs($tempPath);
    
    //         // ✅ Ensure the TemplateProcessor is fully released
    //         unset($template);
    //         gc_collect_cycles(); // force close any file handles
    
    //         // Replace original safely
    //         if (file_exists($filePath)) {
    //             unlink($filePath);
    //         }
    //         rename($tempPath, $filePath);
    
    //         \Log::info("✅ Replacement completed and saved to: {$filePath}");
    //     } catch (\Throwable $e) {
    //         \Log::error("❌ Error replacing DOCX: " . $e->getMessage());
    //     }
    // }
    
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

