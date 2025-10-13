<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientDocument;
use Illuminate\Http\Request;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Storage;

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
        $this->replaceDocxPlaceholders($newFullPath, [
            '{{name}}' => $patient->full_name,
            '{{dob}}' => $patient->date_of_birth,
        ]);
    
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

        return view('patients.documents.edit', compact('patient', 'document', 'templates'));
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
        $zip = new \ZipArchive;

        if ($zip->open($filePath) === true) {
            $content = $zip->getFromName('word/document.xml');

            foreach ($replacements as $key => $value) {
                $content = str_replace($key, $value, $content);
            }

            $zip->addFromString('word/document.xml', $content);
            $zip->close();
        } else {
            throw new \Exception("Could not open DOCX file for placeholder replacement.");
        }
    }


}

