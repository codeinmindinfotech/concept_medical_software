<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class OnlyOfficeController extends Controller
{
    // public function editor($documentId)
    // {
    //     $document = PatientDocument::where('id', $documentId)
    //         ->firstOrFail();
    //     $filePath = $document->file_path;
    //     $fileUrl = secure_asset('storage/' . $filePath);
    //     // $key = 'test-document-key-123';

    //     $key = generateDocumentKey($document);
    //     $token = $this->createJwtToken($document, $key, $fileUrl);
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
    //             'callbackUrl' => url("/api/onlyoffice/callback/{$document->id}"),
    //             'user' => [
    //                 'id' => (string) auth()->user()?->id ?? '1',
    //                 'name' => auth()->user()?->name ?? 'Guest',
    //             ],
    //             'customization' => [
    //                 'forcesave' => true,
    //             ],
    //         ],
    //         'token' => $token, // your JWT token
    //     ];
    //     \Log::info('ONLYOFFICE key: ' . $key);
    //     \Log::info('ONLYOFFICE TOKEN: ' . $token);

    //     return view('docs.editor', compact('config'));
    // }

    // public function callback(Request $request, $type, $fileId)
    // {
    //     Log::info("OnlyOffice callback for {$type} received", $request->all());

    //     $status = $request->get('status');

    //     if (in_array($status, [2, 6])) { // 2 = ready to save, 6 = closed
    //         $url = $request->input('url');

    //         if (!$url) {
    //             Log::error("OnlyOffice callback missing file URL");
    //             return response()->json(['error' => 1, 'message' => 'Missing file URL']);
    //         }

    //         try {
    //             // 🔍 Dynamically load model based on type
    //             switch ($type) {
    //                 case 'patient-document':
    //                     $document = PatientDocument::findOrFail($fileId);
    //                     break;

    //                 case 'template':
    //                 case 'document-template':
    //                     $document = DocumentTemplate::findOrFail($fileId);
    //                     break;

    //                 default:
    //                     throw new \Exception("Invalid document type: {$type}");
    //             }

    //             // ✅ Fetch updated file from OnlyOffice
    //             $newFile = file_get_contents($url);

    //             // ✅ Save back to storage
    //             Storage::disk('public')->put($document->file_path, $newFile);

    //             Log::info("OnlyOffice document saved successfully: {$document->file_path}");

    //         } catch (\Exception $e) {
    //             Log::error("OnlyOffice save failed: " . $e->getMessage());
    //             return response()->json(['error' => 1, 'message' => $e->getMessage()]);
    //         }
    //     }

    //     return response()->json(['error' => 0]);
    // }


    public function callback(Request $request, $documentId = null)
    {
        Log::info('OnlyOffice callback received', $request->all());

        $status = $request->get('status');

        if (in_array($status, [2, 6])) { // 2 = ready to save, 6 = closed
            $url = $request->input('url');
            if ($url) {
                try {
                    $document = PatientDocument::findOrFail($documentId);

                    // Save the new content
                    $newFile = file_get_contents($url);
                    Storage::disk('public')->put($document->file_path, $newFile);

                    Log::info("Document saved: {$document->file_path}");
                } catch (\Exception $e) {
                    Log::error("Failed to save OnlyOffice document: " . $e->getMessage());
                    return response()->json(['error' => 1, 'message' => $e->getMessage()]);
                }
            } else {
                Log::error("OnlyOffice callback missing file URL");
                return response()->json(['error' => 1, 'message' => 'Missing file URL']);
            }
        }

        return response()->json(['error' => 0]);
    }

    public function document_callback(Request $request, $documentId = null)
    {
        Log::info('OnlyOffice callback received', $request->all());

        $status = $request->get('status');

        if (in_array($status, [2, 6])) { // 2 = ready to save, 6 = closed
            $url = $request->input('url');
            if ($url) {
                try {
                    $document = DocumentTemplate::findOrFail($documentId);

                    // Save the new content
                    $newFile = file_get_contents($url);
                    Storage::disk('public')->put($document->file_path, $newFile);

                    Log::info("Document saved: {$document->file_path}");
                } catch (\Exception $e) {
                    Log::error("Failed to save OnlyOffice document: " . $e->getMessage());
                    return response()->json(['error' => 1, 'message' => $e->getMessage()]);
                }
            } else {
                Log::error("OnlyOffice callback missing file URL");
                return response()->json(['error' => 1, 'message' => 'Missing file URL']);
            }
        }

        return response()->json(['error' => 0]);
    }

    // private function createJwtToken($document, $key, $url)
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
    //                 "id" => (string)(auth()->id() ?? 1),
    //                 "name" => auth()->user()?->name ?? 'Guest',
    //             ],
    //         ],
    //         "iat" => time(),
    //         "exp" => time() + 3600,
    //     ];
        

    //     // $payload = [
    //     //     "key" => $key,
    //     //     "id" => (string)(auth()->id() ?? 1),
    //     //     "iat" => time(),
    //     //     "exp" => time() + 3600
    //     // ];
        

    //     return JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
    // }

}