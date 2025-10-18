<?php

namespace App\Http\Controllers;

use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OnlyOfficeController extends Controller
{
    // public function callback(Request $request, PatientDocument $document)
    // {
    //     \Log::info('ONLYOFFICE callback:', $request->all());

    //     if ($request->has('status')) {
    //         // Status 2 means "Document is ready to save"
    //         if ($request->status == 2 && $request->has('url')) {
    //             $updatedFileUrl = $request->input('url');

    //             try {
    //                 $contents = file_get_contents($updatedFileUrl);

    //                 if ($contents === false) {
    //                     \Log::error('Failed to download updated file from URL: ' . $updatedFileUrl);
    //                     return response()->json(['error' => 1]);
    //                 }

    //                 \Storage::put($document->file_path, $contents);

    //                 return response()->json(['error' => 0]);
    //             } catch (\Exception $e) {
    //                 \Log::error('Exception when downloading file: ' . $e->getMessage());
    //                 return response()->json(['error' => 1]);
    //             }
    //         }

    //         // Other statuses
    //         return response()->json(['error' => 0]);
    //     }

    //     return response()->json(['error' => 1]);
    // }

    public function editor($documentId)
    {
        $document = PatientDocument::where('id', $documentId)
        // ->where('patient_id', $patient->id)
        ->firstOrFail();
        // Example: get file info from storage or DB
        $filePath = $document->file_path;
        $fileUrl = asset('storage/' . $filePath);

        $config = [
            'document' => [
                'fileType' => 'docx',
                'key' => $document->id . '-' . strtotime($document->updated_at),
                'title' => 'Document',
                'url' => asset('storage/' . $document->file_path),
            ],
            // "document" => [
            //     "fileType" => "docx",
            //     // "key" => strval(time()), // unique key per version
            //     "title" => "$documentId.docx",
            //     // 'title' => $documentId . '-' . strval(time()).'.docx',
            //     "url" => $fileUrl,
            // ],
            "documentType" => "text",
            "editorConfig" => [
                "callbackUrl" => route('onlyoffice.callback', ['documentId' => $documentId]),
                "user" => [
                    "id" => auth()->id() ?? 1,
                    "name" => auth()->user()?->name ?? "Guest",
                ],
            ],
            "token" => $this->createJwtToken($documentId),
        ];

        return view('docs.editor', compact('config'));
    }

    public function callback(Request $request, $documentId = null)
    {
        Log::info('OnlyOffice callback received', $request->all());

        // Handle save/close events
        $status = $request->get('status');

        if ($status == 2 || $status == 6) { // 2 = ready to save, 6 = forced save
            $url = $request->get('url');
            $newFile = file_get_contents($url);
            Storage::disk('public')->put("documents/{$documentId}.docx", $newFile);
        }

        return response()->json(['error' => 0]);
    }

    private function createJwtToken($documentId)
    {
        $payload = [
            "userid" => auth()->id() ?? 1,
            "file" => $documentId,
            "iat" => time(),
            "exp" => time() + 3600,
        ];

        return \Firebase\JWT\JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
    }

}