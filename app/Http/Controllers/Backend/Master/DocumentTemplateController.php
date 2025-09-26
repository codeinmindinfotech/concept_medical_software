<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
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
        $path = $template->file_path ?? null;
        if(\Storage::disk('public')->exists($path)) {
            $fullInputPath = storage_path('app/public/' . $path);
            // asset('storage/' . $fullInputPath)
            $phpWord = IOFactory::load($fullInputPath);
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        
            ob_start();
            $htmlWriter->save('php://output');
            $htmlContent = ob_get_clean();
        }
    
        return view('documents.show', [
            'html' => $htmlContent,
            'template' => $template
        ]);
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
