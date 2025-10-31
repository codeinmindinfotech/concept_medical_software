<?php

namespace App\Http\Controllers\Backend\Master;

use App\Helpers\OnlyOfficeHelper;
use App\Http\Controllers\Controller;
use App\Mail\PatientDocumentMail;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Response;
use ZipArchive;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

    public function show(string $id)
    {
        $document = DocumentTemplate::findOrFail($id);
        // Check that the file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        $fileUrl = asset('storage/' . $document->file_path);
        $key = OnlyOfficeHelper::generateDocumentKey($document);
        $user = current_user();
        $token = OnlyOfficeHelper::createJwtToken($document, $key, $fileUrl, $user);

        $config = [
            'document' => [
                'fileType' => 'docx',
                'key' => $key, 
                'title' => basename($document->file_path),
                'url' => $fileUrl,
            ],
            'editorConfig' => [
                'mode' => 'view', 
                'callbackUrl' => null,
            ],
            'token' => $token, // your JWT token
        ];

        return view('documents.show', [
            'document' => $document,
            'config' => json_encode($config),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = DocumentTemplate::findOrFail($id);
        $filePath = $template->file_path;
        $fullPath = storage_path('app/public/' . $filePath);

        $fileUrl = secure_asset('storage/' . $filePath);
        $key = OnlyOfficeHelper::generateDocumentKey($template);
        $user = current_user();
        $token = OnlyOfficeHelper::createJwtToken($template, $key, $fileUrl, $user );
        $config = [
            'document' => [
                'storagePath' => storage_path('app/public'),
                'fileType' => 'docx',
                'key' => $key, // MUST be set
                'title' => $template->title ?? 'Document',
                'url' => $fileUrl, // full HTTPS URL
            ],
            'documentType' => 'word',
            'editorConfig' => [
                'mode' => 'edit',
                'callbackUrl' => url("/api/onlyoffice/callback/template/{$template->id}"),
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
        return view('documents.edit', compact('template','config'));
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
    public function downloadSelectedDocuments(Request $request)
    {
        $request->validate([
            'selected_doc' => 'required|exists:document_templates,id',
            'newTemplateDescription' => 'nullable|string|max:255',
        ]);
    
        $template = DocumentTemplate::findOrFail($request->selected_doc);
    
        // Create new template if description provided
        if ($request->newTemplateDescription) {
            $newTemplate = DocumentTemplate::create([
                'name' => $request->newTemplateDescription,
                'type' => $template->type,
                'file_path' => '', // will be filled with copied file
            ]);
    
            // Copy selected document as base
            $originalFile = $template->file_path;
            $copyPath = 'document_templates/' . uniqid('template_') . '.' . pathinfo($originalFile, PATHINFO_EXTENSION);
            \Storage::disk('public')->copy($originalFile, $copyPath);
    
            $newTemplate->update(['file_path' => $copyPath]);
    
            // $template = $newTemplate; // Only download new template
        }
    
        // Return the file for download
        // $fileFullPath = storage_path('app/public/' . $template->file_path);
        // $fileName = $template->name . '.' . pathinfo($template->file_path, PATHINFO_EXTENSION);
        return redirect()->route('documents.index')->with('success', 'Template updated successfully');

        // return response()->download($fileFullPath, $fileName);
    }
    
        // // Create ZIP
        // $zipFileName = 'documents_' . now()->format('YmdHis') . '.zip';
        // $zipPath = storage_path('app/public/' . $zipFileName);
        // $zip = new ZipArchive;
        // if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
        //     foreach ($templates as $template) {
        //         $file = storage_path('app/public/' . $template->file_path);
        //         if(file_exists($file)){
        //             $zip->addFile($file, basename($file));
        //         }
        //     }
        //     $zip->close();
        // }

        // return response()->download($zipPath)->deleteFileAfterSend(true);

        public function emailSelectedDocuments(Request $request)
        {
            $request->validate([
                'email_to' => 'required|email',
                'email_docs' => 'required|array',
                'email_docs.*' => 'exists:document_templates,id',
            ]);
        
            $documents = DocumentTemplate::whereIn('id', $request->email_docs)->get();
        
            // Prepare a "dummy" patient object with name/email
            $patient = (object)[
                'first_name' => 'Valued',
                'surname' => 'Patient',
                'email' => $request->email_to,
            ];
        
            $messageContent = "Please find your requested documents attached.";
        
            Mail::to($patient->email)->send(new PatientDocumentMail($patient, $documents, $messageContent));
        
            return redirect()->back()->with('success', 'Documents emailed successfully.');
        }
        

}
