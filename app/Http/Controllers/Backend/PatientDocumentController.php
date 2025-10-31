<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\KeywordHelper;
use App\Helpers\OnlyOfficeHelper;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientDocument;
use Illuminate\Http\Request;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;
use Illuminate\Http\RedirectResponse;
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
    
        // 🔁 Replace placeholders
        // $this->replaceDocxPlaceholders($newFullPath, $replacements);
        KeywordHelper::replaceKeywords($newFullPath, $patient);

    
        // 💾 Save in database
        PatientDocument::create([
            'patient_id' => $patient->id,
            'document_template_id' => $template->id,
            'file_path' => $newStoragePath,
        ]);
    
        return response()->json([
            'redirect' => guard_route('patient-documents.index', $patient),
            'message' => 'Documents Created successfully',
        ]);
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

        \Log::info("Replaced DOCX saved at: {$fullPath}, size: " . filesize($fullPath));

        // // Optional: return the file for download to manually check
        // return response()->download($fullPath);

        $key = OnlyOfficeHelper::generateDocumentKey($document);
        $token = OnlyOfficeHelper::createJwtToken($document, $key, $fileUrl, $patient);
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

    // private function createJwtToken($document, $key, $url, $patient)
    // {
    //     $payload = [
    //         "document" => [
    //             "fileType" => "docx",
    //             "key" => $key,
    //             "title" => $document->title ?? 'Document',
    //             "url" => $url,
    //         ],
    //         "editorConfig" => [
    //             "callbackUrl" => url("/api/onlyoffice/callback/{$document->id}"),
    //             "mode" => "edit",
    //             "user" => [
    //                 'id' => (string) $patient->id ?? '1',
    //                 'name' => $patient->full_name ?? 'Guest',
    //             ],
    //         ],
    //         "iat" => time(),
    //         "exp" => time() + 3600,
    //     ];
        
    //     return JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
    // }

    public function update(Request $request, Patient $patient, PatientDocument $document)
    {

        // Only update if a new template is selected
        if ($request->has('document_template_id') 
            && $request->document_template_id != $document->document_template_id) {
    
                
            $template = DocumentTemplate::findOrFail($request->document_template_id);
            $templatePath = storage_path('app/public/' . $template->file_path);
    
            if (!is_file($templatePath)) {
                return response()->json(['error' => 'Template file does not exist.'], 500);
            }

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
    
            KeywordHelper::replaceKeywords($newFullPath, $patient);
    
            // Update database
            $document->update([
                'document_template_id' => $template->id,
            ]);
        }
    
        return response()->json([
            'redirect' => guard_route('patient-documents.index', $patient),
            'message' => 'Document updated successfully and auto-saved via OnlyOffice',
        ]);
    }
    
    public function destroy(Patient $patient, PatientDocument $document): RedirectResponse
    {
        abort_if($document->patient_id !== $patient->id, 403);

        Storage::delete("public/" . $document->file_path);
        $document->delete();

        return redirect()
            ->route('patient-documents.index', $patient)
            ->with('success', 'Document deleted successfully.');

    }

    public function previewTemplate(Request $request, Patient $patient, PatientDocument $document)
    {
        $request->validate([
            'template_id' => 'required|exists:document_templates,id'
        ]);

        $template = DocumentTemplate::findOrFail($request->template_id);
        
        // Create a temporary copy for preview
        $tempFileName = 'temp_preview_' . uniqid() . '.docx';
        $tempFilePath = "patient_docs/temp/{$tempFileName}";
        $fullTempPath = storage_path('app/public/' . $tempFilePath);

        // Ensure temp folder exists
        if (!file_exists(dirname($fullTempPath))) {
            mkdir(dirname($fullTempPath), 0775, true);
        }

        copy(storage_path('app/public/' . $template->file_path), $fullTempPath);

        // Replace placeholders
        KeywordHelper::replaceKeywords($fullTempPath, $patient);

        return response()->json([
            'preview_url' => secure_asset('storage/' . $tempFilePath)
        ]);
    }

    public function previewTemplateCreate(Request $request, Patient $patient)
    {
        $request->validate([
            'template_id' => 'required|exists:document_templates,id',
        ]);

        $template = DocumentTemplate::findOrFail($request->template_id);
        $templatePath = storage_path('app/public/' . $template->file_path);

        // Create a temporary filename
        $tempFileName = 'temp_preview_' . uniqid() . '.docx';
        $tempStoragePath = "patient_docs/temp/{$tempFileName}";
        $tempFullPath = storage_path('app/public/' . $tempStoragePath);

        // Ensure temp folder exists
        $directoryPath = storage_path('app/public/patient_docs/temp');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0775, true);
        }

        // Copy template to temp location
        if (!copy($templatePath, $tempFullPath)) {
            return response()->json(['error' => 'Could not create preview file.'], 500);
        }

        // Optionally replace placeholders with patient info
        KeywordHelper::replaceKeywords($tempFullPath, $patient);

        // Return URL for OnlyOffice preview
        return response()->json([
            'preview_url' => secure_asset('storage/' . $tempStoragePath)
        ]);
    }

}

