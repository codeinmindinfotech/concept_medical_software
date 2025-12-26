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
use setasign\Fpdi\Tcpdf\Fpdi;

class PatientDocumentController extends Controller
{
    public function index(Patient $patient)
    {
        $documents = $patient->documents()
                            ->whereNotNull('file_path')
                            ->where('file_path', '!=', '')
                            ->with('template')
                            ->orderBy('id', 'desc')
                            ->get();
                            
        return view(guard_view('patients.documents.index', 'patient_admin.document.index'), compact('documents', 'patient'));
    }

    public function create(Request $request, Patient $patient)
    {
        $appointmentTypeId = $request->query('appointment_type'); // may be null

        $templates = DocumentTemplate::when($appointmentTypeId, function ($q) use ($appointmentTypeId) {
            $q->where('appointment_type', $appointmentTypeId);
        })->get();

        // $templates = DocumentTemplate::companyOnly()->get();

        $documents = PatientDocument::where('patient_id', $patient->id)
            ->where(function($q) {
                $q->whereNull('file_path')
                ->orWhere('file_path', '');
            })
            ->get();

        foreach ($documents as $document) {
            if ($document->tempPath && Storage::disk('public')->exists($document->tempPath)) {
                Storage::disk('public')->delete($document->tempPath);
            }
            $document->delete();
        }


        $document = PatientDocument::create([
            'patient_id' => $patient->id,
            'file_path' => '', 
            'document_template_id' => $templates->first()->id ?? null,   
            'company_id' => auth()->user()->company_id ?? null,
        ]);
        
        $fileUrl = '';

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
        return view(guard_view('patients.documents.create', 'patient_admin.document.create'), compact('patient','appointmentTypeId', 'templates', 'config', 'document', 'token'));
    }

    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'document_template_id' => 'required|exists:document_templates,id',
            'document_id' => 'required|exists:patient_documents,id',
        ]);
        $documentId = $request->input('document_id'); 
        $document = PatientDocument::findOrFail($documentId);
        $document->update([
            'document_template_id' => $request->document_template_id,
        ]);
    
        return response()->json([
            'redirect' => guard_route('patient-documents.index', $patient),
            'message' => 'Documents Created successfully',
        ]);
    }

    public function edit(Patient $patient, $documentId)
    {
        $appointmentTypeId = null;
        $templates = DocumentTemplate::companyOnly()->get();
        $document = PatientDocument::where('id', $documentId)
            ->where('patient_id', $patient->id)
            ->firstOrFail();
        
        abort_if($document->patient_id !== $patient->id, 403);

        $filePath = $document->file_path;
        $fileUrl = secure_asset('storage/' . $filePath) . '?v=' . time();

        $callback = url("/api/onlyoffice/callback?document_id=" . $document->id);
        \Log::info('OnlyOffice callback URL: ' . $callback);   

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
                'callbackUrl' => $callback,
                'user' => [
                    'id' => (string) $user->id ?? '1',
                    'name' => $user->name ?? 'Guest',
                ],
                'customization' => [
                    'forcesave' => true,
                ],
            ],
            'token' => $token, 
        ];
        return view(guard_view('patients.documents.edit', 'patient_admin.document.edit'), compact('patient', 'document', 'templates', 'config', 'token', 'appointmentTypeId'));
    }

    public function update(Request $request, Patient $patient, PatientDocument $document)
    {
        $filePath = $document->file_path;
        $fullDestinationPath = storage_path('app/public/' . $filePath); // Use filesystem path
        // Replace placeholders with patient data
        KeywordHelper::replaceKeywords($fullDestinationPath, $patient);
        
        if ($request->has('document_template_id') 
            && $request->document_template_id != $document->document_template_id) {
    
                
            $template = DocumentTemplate::findOrFail($request->document_template_id);
           
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

        return redirect(guard_route('patient-documents.index', $patient))
            ->with('success', 'Document deleted successfully.');

    }

    public function previewTemplateCreate(Request $request, Patient $patient)
    {
        $request->validate([
            'template_id' => 'required|exists:document_templates,id',
            'document_id' => 'required|exists:patient_documents,id',
        ]);
        
        $documentId = $request->input('document_id'); // ✅ use input(), not query()
        $document = PatientDocument::findOrFail($documentId);

        $template = DocumentTemplate::findOrFail($request->template_id);
        $sourcePath = storage_path('app/public/' . $template->file_path);
        
        $destinationFolder = company_path('patient_docs');
        
        // Make sure the folder exists
        $fullDestinationDir = storage_path('app/public/' . $destinationFolder);
        if (!file_exists($fullDestinationDir)) {
            mkdir($fullDestinationDir, 0775, true);
        }
        
        // Create a unique new filename
        $newFileName = uniqid('document_') . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
        $destinationPath = $destinationFolder . '/' . $newFileName;
        $fullDestinationPath = storage_path('app/public/' . $destinationPath); // Use filesystem path

        if (!copy($sourcePath, $fullDestinationPath)) {
            return response()->json(['success' => false, 'message' => 'Could not copy file.'], 500);
        }

        
        // Update document with new path and template reference
        $data['file_path'] = $destinationPath;
        $data['document_template_id'] = $request->template_id ?? $template->id;
        $res = $document->update($data);
        \Log::info('docuemnt updated : ' . $res);
        \Log::info('document saved', $document->toArray());

        // Replace placeholders with patient data
        KeywordHelper::replaceKeywords($fullDestinationPath, $patient);

        // OnlyOffice URL
        $fileUrl = asset('storage/' . $destinationPath) . '?v=' . time();
        $key = OnlyOfficeHelper::generateDocumentKey($document, true);
        $token = OnlyOfficeHelper::createJwtToken($document, $key, $fileUrl, $patient);


        \Log::info('preview ', [ 'documentId' => $documentId,'url' => $fileUrl]);

        return response()->json([
            'success' => true,
            'url' => $fileUrl,
            'fileType' => 'docx',
            'key' => $key,
            'token' => $token,
            'title' => $template->name,
            'callbackUrl' => url('/api/onlyoffice/callback') . '?document_id=' . $documentId
        ]);
    }

    public function emailForm(Patient $patient, PatientDocument $document)
    {
        $doctorEmail = $patient->doctor?->email ?? '';
        $referralEmail = $patient->referralDoctor?->email ?? '';
        $otherEmail = $patient->otherDoctor?->email ?? '';
        $patientEmail = $patient->email ?? '';
        return view(guard_view('patients.documents.email', 'patient_admin.document.email'), compact(
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
    
        // DOCX path
        $docxPath = storage_path('app/public/' . $document->file_path);
        if (!file_exists($docxPath)) {
            return back()->with('error', 'Document file not found.');
        }
    
        // Convert DOCX → PDF
        $pdfPath = $this->downloadConvertedPdf($docxPath);
        if (!$pdfPath || !file_exists($pdfPath)) {
            return back()->with('error', 'Conversion to PDF failed.');
        }
    
        // Default: normal PDF
        $finalPdfPath = $pdfPath;
        $isProtected = false;
    
        // 🔐 OPTIONAL company password
        $company = $patient->company;
    
        if ($company && $company->document_password) {
            $password = decrypt($company->document_password);
            $finalPdfPath = $this->protectPdf($pdfPath, $password);
            $isProtected = true;
        }
    
        // Send email
        Mail::to($validated['to_email'])
            ->cc($this->parseEmails($validated['cc'] ?? ''))
            ->bcc($this->parseEmails($validated['bcc'] ?? ''))
            ->send(new PatientDocumentMail(
                $validated,
                $finalPdfPath,
                $document,
                $isProtected
            ));
    
        // Cleanup temp files
        @unlink($pdfPath);
    
        if ($finalPdfPath !== $pdfPath) {
            @unlink($finalPdfPath);
        }
    
        return redirect()
            ->route('patient-documents.email.form', [$patient, $document])
            ->with('success', 'Email sent successfully with attached PDF document!');
    }

    private function protectPdf(string $inputPdf, string $password): string
    {
        $outputDir = storage_path('app/temp');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $outputPath = $outputDir . '/protected_' . uniqid() . '.pdf';

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($inputPdf);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tpl = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($tpl);
        }

        // 🔐 Password required to open
        $pdf->SetProtection(['print', 'copy'], $password);

        $pdf->Output($outputPath, 'F');

        return $outputPath;
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
    public function convertDocxToPdfOnlyOffice($docxPath)
    {
        if (!file_exists($docxPath)) {
            return back()->with('error', 'Document not found.');
        }

        $docxUrl = asset('storage/' . str_replace(storage_path('app/public') . '/', '', $docxPath)); 
        $pdfDir = storage_path('app/public/patient_docs/pdf');
        if (!file_exists($pdfDir)) mkdir($pdfDir, 0777, true);

        $pdfPath = $pdfDir . '/' . pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf';

        $payload = [
            'async' => false,
            'filetype' => 'docx',
            'key' => uniqid(),
            'outputtype' => 'pdf',
            'title' => pathinfo($pdfPath, PATHINFO_BASENAME),
            'url' => $docxUrl,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, rtrim(config('onlyoffice.server_url'), '/').'/web-apps/apps/api/documents/api/convert');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['fileUrl'])) {
            file_put_contents($pdfPath, fopen($result['fileUrl'], 'r'));
            echo $pdfPath;
            return response()->download($pdfPath);
        }

        if (!is_array($result)) {
            $result = ['response' => $result];
        }
        \Log::error('OnlyOffice PDF conversion failed', $result);

        return back()->with('error', 'OnlyOffice conversion failed.');
    }

}

