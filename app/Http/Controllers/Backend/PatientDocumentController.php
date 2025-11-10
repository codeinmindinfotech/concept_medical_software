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
use Illuminate\Http\RedirectResponse;
use App\Mail\PatientDocumentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

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
        $config = []; // 👈 define empty config to avoid undefined variable error
        return view('patients.documents.create', compact('patient', 'templates', 'config'));
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
        $fileUrl = secure_asset('storage/' . $filePath) . '?v=' . time();

        $callback = url("/api/onlyoffice/callback?document_id=" . $document->id);

        $key = OnlyOfficeHelper::generateDocumentKey($document, true);
        $user = current_user();
        $token = OnlyOfficeHelper::createJwtToken($document, $key, $fileUrl, $user );
        $config = [
            'document' => [
                'storagePath' => storage_path('app/public'),
                'fileType' => 'docx',
                'key' => $key,
                'title' => $document->title ?? 'Document',
                'url' => $fileUrl,
            ],
            'documentType' => 'word',
            'editorConfig' => [
                'mode' => 'edit',
                'callbackUrl' => $callback,//url("/api/onlyoffice/document_callback/{$template->id}"),
                'user' => [
                    'id' => (string) $user->id ?? '1',
                    'name' => $user->name ?? 'Guest',
                ],
                'customization' => [
                    'forcesave' => true,
                ],
            ],
            'token' => $token, // your JWT token
        ];
        return view('patients.documents.edit', compact('patient', 'document', 'templates', 'config', 'token'));
    }

    // public function edit(Patient $patient, $documentId)
    // {
    //     $templates = DocumentTemplate::all();
    //     $document = PatientDocument::where('id', $documentId)
    //         ->where('patient_id', $patient->id)
    //         ->firstOrFail();

    //     abort_if($document->patient_id !== $patient->id, 403);
        
    //     $filePath = $document->file_path;
    //     $fullPath = storage_path('app/public/' . $filePath);

    //     KeywordHelper::replaceKeywords($fullPath, $patient);
    //     $fileUrl = secure_asset('storage/' . $filePath). '?v=' . time();

    //     \Log::info("Replaced DOCX saved at: {$fullPath}, size: " . filesize($fullPath));

    //     $callback = url("/api/onlyoffice/callback?document_id=" . $document->id);

    //     $key = OnlyOfficeHelper::generateDocumentKey($document);
    //     $token = OnlyOfficeHelper::createJwtToken($document, $key, $fileUrl, $patient);
    //     $config = [
    //         'document' => [
    //             'storagePath' => storage_path('app/public'),
    //             'fileType' => 'docx',
    //             'key' => $key, // MUST be set
    //             'title' => $document->title ?? 'Document',
    //             'url' => $fileUrl, // full HTTPS URL
    //         ],
    //         'documentType' => 'word',
    //         'editorConfig' => [
    //             'mode' => 'edit',
    //             'callbackUrl' => $callback,//url("/api/onlyoffice/callback/{$document->id}"),
    //             'user' => [
    //                 'id' => (string) $patient->id ?? '1',
    //                 'name' => $patient->full_name ?? 'Guest',
    //             ],
    //             'customization' => [
    //                 'forcesave' => true,
    //             ],
    //         ],
    //         'token' => $token // your JWT token
    //     ];
    //     \Log::info('ONLYOFFICE key: ' . $key);
    //     \Log::info('patient ONLYOFFICE TOKEN: ' . $token);

    //     return view('patients.documents.edit', compact('patient', 'document', 'templates', 'config', 'token'));
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

            $newFileName = uniqid('patient_doc_') . '.docx';
            $newStoragePath = "patient_docs/{$newFileName}";
            $newFullPath = storage_path("app/public/{$newStoragePath}");
    
            $directoryPath = storage_path('app/public/patient_docs');
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0775, true);
            }
        
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

    public function previewTemplateCreate(Request $request, Patient $patient)
    {
        $request->validate([
            'template_id' => 'required|exists:document_templates,id',
        ]);

        $template = DocumentTemplate::findOrFail($request->template_id);
        $templatePath = storage_path('app/public/' . $template->file_path);

        // Create temporary file
        $tempFileName = 'temp_preview_' . uniqid() . '.docx';
        $tempStoragePath = "patient_docs/temp/{$tempFileName}";
        $tempFullPath = storage_path('app/public/' . $tempStoragePath);

        // Ensure temp folder exists
        if (!file_exists(storage_path('app/public/patient_docs/temp'))) {
            mkdir(storage_path('app/public/patient_docs/temp'), 0775, true);
        }

        if (!copy($templatePath, $tempFullPath)) {
            return response()->json(['success' => false, 'message' => 'Could not copy template file.'], 500);
        }

        // Replace placeholders with patient data
        KeywordHelper::replaceKeywords($tempFullPath, $patient);

        // Generate OnlyOffice key & token
        $key = OnlyOfficeHelper::generateDocumentKey(['file_path' => $tempStoragePath]);
        $token = OnlyOfficeHelper::createJwtToken(['file_path' => $tempStoragePath], $key, secure_asset('storage/' . $tempStoragePath), $patient);

        return response()->json([
            'success' => true,
            'url' => secure_asset('storage/' . $tempStoragePath),
            'fileType' => 'docx',
            'key' => $key,
            'token' => $token,
            'title' => $template->name,
        ]);
    }

    public function emailForm(Patient $patient, PatientDocument $document)
    {
        $doctorEmail = $patient->doctor?->email ?? '';
        $referralEmail = $patient->referralDoctor?->email ?? '';
        $otherEmail = $patient->otherDoctor?->email ?? '';
        $patientEmail = $patient->email ?? '';
        return view('patients.documents.email', compact(
            'patient', 
            'document',
            'patientEmail',
            'doctorEmail',
            'referralEmail',
            'otherEmail'
        ));
    }

    public function sendEmail(Request $request, Patient $patient, PatientDocument $document)
    {
        $validated = $request->validate([
            'sender_email' => 'nullable|email',
            'to_email'     => 'required|email',
            'subject'      => 'nullable|string|max:255',
            'message'      => 'nullable|string',
            'cc'           => 'nullable|string',
            'bcc'          => 'nullable|string',
        ]);

        // Full path to stored .docx file
        $docxPath = storage_path('app/public/' . $document->file_path);
        if (!file_exists($docxPath)) {
            return back()->with('error', 'Document file not found.');
        }

        $pdfPath = $this->downloadConvertedPdf($docxPath);
        if (!$pdfPath || !file_exists($pdfPath)) {
            return back()->with('error', 'Conversion to PDF failed via OnlyOffice.');
        }

        // Send email
        Mail::to($validated['to_email'])
            ->cc($this->parseEmails($validated['cc'] ?? ''))
            ->bcc($this->parseEmails($validated['bcc'] ?? ''))
            ->send(new PatientDocumentMail($validated, $pdfPath, $document));

        // cleanup
        unlink($pdfPath);

        return redirect()
            ->route('patient-documents.email.form', [$patient, $document])
            ->with('success', 'Email sent successfully with attached PDF document!');
    }

    private function parseEmails(string $emails): array
    {
        return array_filter(array_map('trim', explode(',', $emails)));
    }


    public function downloadConvertedPdf($docxPath)
    {
        if (!file_exists($docxPath)) {
            return back()->with('error', 'Document file not found.');
        }
    
        // Directory to save temporary PDFs
        $pdfDir = storage_path('app/temp');
        if (!file_exists($pdfDir)) mkdir($pdfDir, 0777, true);
    
        $pdfPath = $pdfDir . '/' . pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf';
    
        try {
            $pdfRendererName = Settings::PDF_RENDERER_DOMPDF;
            $pdfRendererPath = base_path('vendor/dompdf/dompdf'); // DomPDF path
            Settings::setPdfRenderer($pdfRendererName, $pdfRendererPath);
    
            // Load DOCX and save as PDF
            $phpWord = IOFactory::load($docxPath);
            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($pdfPath);
            return $pdfPath;

    
        } catch (\Exception $e) {
            \Log::error('DOCX to PDF conversion failed: ' . $e->getMessage());
            return back()->with('error', 'Conversion to PDF failed: ' . $e->getMessage());
        }
    }
}

