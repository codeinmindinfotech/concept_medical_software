<?php

namespace App\Http\Controllers\Backend\Master;

use App\Helpers\OnlyOfficeHelper;
use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Response;
use ZipArchive;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Str;


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
            'tempPath' => 'nullable|string',
        ]);
        
        if ($request->filled('tempPath') && Storage::disk('public')->exists($request->tempPath)) {
            $extension = pathinfo($request->tempPath, PATHINFO_EXTENSION);
            $filePath = company_path('document_templates/' . uniqid('template_') . '.' . $extension);
            // $filePath = 'document_templates/' . uniqid('template_') . '.' . $extension;
            Storage::disk('public')->copy($request->tempPath, $filePath);
            Storage::disk('public')->delete($request->tempPath); // optional cleanup
        } else {
            // fallback: uploaded file
            $filePath = $request->file('file')->storeAs(
                company_path('document_templates'),
                uniqid('template_') . '.' . $request->file('file')->getClientOriginalExtension(),
                'public'
            );
            // $filePath = $request->file('file')->store('document_templates', 'public');
        }

        DocumentTemplate::create([
            'name' => $request->name,
            'type' => $request->type,
            'file_path' => $filePath,
            'company_id' => auth()->user()->company_id ?? null,
        ]);

        return response()->json([
            'redirect' => guard_route('documents.index'),
            'message' => 'Template created successfully',
        ]);
    }

    public function show(string $id)
    {
        $document = DocumentTemplate::findOrFail($id);
        // Check that the file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        $fileUrl = asset('storage/' . $document->file_path);
        $key = OnlyOfficeHelper::generateDocumentKey($document, true);
        $user = current_user();
        $token = OnlyOfficeHelper::createJwtTokenDocumentTemplate($document, $key, $fileUrl, $user);

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
        $key = OnlyOfficeHelper::generateDocumentKey($template, true);
        $user = current_user();
        $token = OnlyOfficeHelper::createJwtTokenDocumentTemplate($template, $key, $fileUrl, $user );
        $config = [
            'document' => [
                'storagePath' => storage_path('app/public'),
                'fileType' => 'docx',
                'key' => $key,
                'title' => $template->title ?? 'Document',
                'url' => $fileUrl,
            ],
            'documentType' => 'word',
            'editorConfig' => [
                'mode' => 'edit',
                'callbackUrl' => url("/api/onlyoffice/document_callback/{$template->id}"),
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
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
        
            $filePath = $request->file('file')->storeAs(
                company_path('document_templates'),
                uniqid('template_') . '.' . $request->file('file')->getClientOriginalExtension(),
                'public'
            );
            $data['file_path'] = $filePath;
        }
        

        $document->update($data);

        return response()->json([
            'redirect' => guard_route('documents.index'),
            'message' => 'Template updated successfully',
        ]);
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
            $copyPath = company_path('document_templates/' . uniqid('template_') . '.' . pathinfo($originalFile, PATHINFO_EXTENSION));
            Storage::disk('public')->copy($originalFile, $copyPath);
            $newTemplate->update(['file_path' => $copyPath]);

        }
    
        // Return the file for download
        // $fileFullPath = storage_path('app/public/' . $template->file_path);
        // $fileName = $template->name . '.' . pathinfo($template->file_path, PATHINFO_EXTENSION);
        return redirect()->route('documents.index')->with('success', 'Template updated successfully');

        // return response()->download($fileFullPath, $fileName);
    }
    
    public function tempUpload(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:doc,docx,pdf|max:2048']);

        // Store temporary
        $file = $request->file('file');
        // $tempPath = $file->store('temp', 'public');
        $tempPath = $file->storeAs(
            company_path('temp'),
            uniqid('temp_') . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        $tempTemplate = new DocumentTemplate();
        $tempTemplate->id = Str::random(32);
        $tempTemplate->file_path = $tempPath;
        $tempTemplate->created_at = now();
        $tempTemplate->updated_at = now();

        $fileUrl = asset('storage/' . $tempPath);
        $key = OnlyOfficeHelper::generateDocumentKey($tempTemplate,true);
        $token = OnlyOfficeHelper::createJwtTokenDocumentTemplate($tempTemplate, $key, $fileUrl, current_user());

        return response()->json([
            'success' => true,
            'url' => $fileUrl,
            'key' => $key,
            'token' => $token,
            'fileType' => pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION),
            'tempPath' => $tempPath,
            'callbackUrl' => url('/api/onlyoffice/document_callback') . '?tempPath=' . urlencode($tempPath)
        ]);
    }

    public function loadFile($id)
    {
        $template = DocumentTemplate::find($id);
        $filePath = $template->file_path;

        if (!$template || !$filePath) {
            return response()->json([
                'success' => false,
                'message' => 'Template file not found'
            ]);
        }
        $fullPath = storage_path('app/public/' . $filePath);

        $fileUrl = secure_asset('storage/' . $filePath);
        $key = OnlyOfficeHelper::generateDocumentKey($template,true);
        $user = current_user();
        $token = OnlyOfficeHelper::createJwtTokenDocumentTemplate($template, $key, $fileUrl, $user );
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
                'callbackUrl' => url("/api/onlyoffice/document_callback/{$template->id}"),
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

        return response()->json([
            'success' => true,
            'fileType' => pathinfo($template->file_path, PATHINFO_EXTENSION),
            'key' => $key,
            'title' => $template->name,
            'url' => asset('storage/' . $template->file_path),
            'token' => $token
        ]);
    }

    public function doc()
    {
        $id = 2;
        $template = DocumentTemplate::findOrFail($id);
         // Update updated_at to generate a new key
         $template->touch();
        $fileName = $template->file_path;
        $callback = url("/api/onlyoffice/callback_new?file=" . urlencode($fileName));

        // Make sure this file exists in storage/app/public/
        $fileUrl = asset('storage/' . $fileName) . '?v=' . time();

        $key = OnlyOfficeHelper::generateDocumentKey($template, true);
        $user = current_user();
        $token = OnlyOfficeHelper::createJwtTokenDoc($template, $key, $fileUrl, $user );
        $config = [
            'document' => [
                'storagePath' => storage_path('app/public'),
                'fileType' => 'docx',
                'key' => $key,
                'title' => $template->title ?? 'Document',
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
            'token' => $token, // your JWT token
        ];

        return view('documents.doc', compact('config'));
    }

    
}
