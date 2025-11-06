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

        $status = $request->input('status');
        $url = $request->input('url');
        $tempPath = $request->query('tempPath');

        // Only process when OnlyOffice says file is saved or closed
        if (!in_array($status, [2, 6]) || !$url) {
            return response()->json(['error' => 0]);
        }

        try {
            $fileContents = file_get_contents($url);
            if (!$fileContents) {
                throw new \Exception("Failed to download file from OnlyOffice URL");
            }

            // 🧠 CASE 1: Editing an existing saved template
            if ($documentId) {
                $document = DocumentTemplate::find($documentId);
                if (!$document) {
                    throw new \Exception("DocumentTemplate not found for ID: {$documentId}");
                }

                $storagePath = 'public/' . $document->file_path;
                Storage::put($storagePath, $fileContents);
                Log::info("✅ Updated existing template: {$storagePath}");
            }

            // 🧠 CASE 2: Editing a temp upload before saving template
            elseif ($tempPath) {
                $storagePath = 'public/' . ltrim($tempPath, '/');
                Storage::put($storagePath, $fileContents);
                Log::info("✅ Updated temp file: {$storagePath}");
            }

        } catch (\Exception $e) {
            Log::error("❌ OnlyOffice callback failed: " . $e->getMessage());
            return response()->json(['error' => 1, 'message' => $e->getMessage()]);
        }

        return response()->json(['error' => 0]);
    }

    // public function document_callback(Request $request, $documentId = null)
    // {
    //     Log::info('OnlyOffice callback received', $request->all());

    //     $status = $request->get('status');           // OnlyOffice status code
    //     $url = $request->input('url');               // URL of the updated document
    //     $tempPath = $request->query('tempPath');     // temp path we sent to OnlyOffice

    //     if (!$url || !$tempPath) {
    //         return response()->json([
    //             'error' => 1,
    //             'message' => 'Missing URL or tempPath'
    //         ]);
    //     }

    //     // Status 2 = ready to save, 6 = closed
    //     if (in_array($status, [2, 6])) {
    //         try {
    //             // Download the updated file from OnlyOffice
    //             $fileContents = file_get_contents($url);
    //             if (!$fileContents) {
    //                 throw new \Exception("Failed to download file from OnlyOffice URL");
    //             }

    //             // Save the file to temporary storage instead of overwriting original
    //             Storage::disk('public')->put($tempPath, $fileContents);

    //             Log::info("OnlyOffice document saved to temp path: {$tempPath}");

    //             // Optionally, you could store the temp path in DB or session
    //             if ($documentId) {
    //                 $document = DocumentTemplate::find($documentId);
    //                 if ($document) {
    //                     $document->file_path = $tempPath; // if you have this column
    //                     $document->save();
    //                 }
    //             }

    //         } catch (\Exception $e) {
    //             Log::error("Failed to save OnlyOffice document: " . $e->getMessage());
    //             return response()->json([
    //                 'error' => 1,
    //                 'message' => $e->getMessage()
    //             ]);
    //         }
    //     }

    //     return response()->json(['error' => 0]);
    // }


    // public function document_callback(Request $request, $documentId = null)
    // {
    //     Log::info('OnlyOffice callback received', $request->all());

    //     $status = $request->get('status');

    //     if (in_array($status, [2, 6])) { // 2 = ready to save, 6 = closed
    //         $url = $request->input('url');
    //         if ($url) {
    //             try {
    //                 $document = DocumentTemplate::findOrFail($documentId);

    //                 // Save the new content
    //                 $newFile = file_get_contents($url);
    //                 Storage::disk('public')->put($document->file_path, $newFile);

    //                 Log::info("Document saved: {$document->file_path}");
    //             } catch (\Exception $e) {
    //                 Log::error("Failed to save OnlyOffice document: " . $e->getMessage());
    //                 return response()->json(['error' => 1, 'message' => $e->getMessage()]);
    //             }
    //         } else {
    //             Log::error("OnlyOffice callback missing file URL");
    //             return response()->json(['error' => 1, 'message' => 'Missing file URL']);
    //         }
    //     }

    //     return response()->json(['error' => 0]);
    // }
}