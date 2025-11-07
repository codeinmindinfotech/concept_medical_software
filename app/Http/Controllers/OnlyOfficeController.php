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
        Log::info("Onlyoffice callback status document {$status}");
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
        $status = $request->input('status');
        $url = $request->input('url');
        $tempPath = $request->query('tempPath');

        Log::info("OnlyOffice callback | status: {$status}, documentId: {$documentId}, tempPath: {$tempPath}");

        if (!in_array($status, [2, 4, 6])) {
            Log::info("Ignoring status: {$status}");
            return response()->json(['error' => 0]);
        }

        try {
            $content = $url ? file_get_contents($url) : file_get_contents('php://input');
            if (!$content) throw new \Exception("Empty file content");

            if ($documentId) {
                $document = DocumentTemplate::findOrFail($documentId);
                $filePath = company_path($document->file_path); 
                Storage::disk('public')->put(ltrim($filePath, '/'), $content);
                // Storage::disk('public')->put(ltrim($document->file_path, '/'), $content);
                Log::info("Updated existing template: {$document->file_path}");
            } elseif ($tempPath) {
                Storage::disk('public')->put(ltrim($tempPath, '/'), $content);
                Log::info("Updated temp file: {$tempPath}");
            } else {
                throw new \Exception("No documentId or tempPath provided");
            }

            Log::info("Saved document (size: " . strlen($content) . " bytes)");

        } catch (\Exception $e) {
            Log::error("OnlyOffice callback failed: " . $e->getMessage());
            return response()->json(['error' => 1, 'message' => $e->getMessage()]);
        }

        return response()->json(['error' => 0]);
    }



    // public function document_callback(Request $request, $documentId = null)
    // {
    //     Log::info('OnlyOffice document callback received', $request->all());

    //     $status = $request->input('status');
    //     $url = $request->input('url');
    //     $tempPath = $request->query('tempPath');
    //     Log::info("Onlyoffice callback status {$status}");
    //     // Only process when OnlyOffice says file is saved or closed
    //     if (!in_array($status, [2, 6]) || !$url) {
    //         Log::info("Only process when OnlyOffice says file is saved or closed");

    //         return response()->json(['error' => 0]);
    //     }

    //     try {
    //         $fileContents = file_get_contents($url);
    //         if (!$fileContents) {
    //             throw new \Exception("Failed to download file from OnlyOffice URL");
    //         }

    //         // 🧠 CASE 1: Editing an existing saved template
    //         if ($documentId) {
    //             $document = DocumentTemplate::find($documentId);
    //             if (!$document) {
    //                 throw new \Exception("DocumentTemplate not found for ID: {$documentId}");
    //             }

    //             $storagePath = 'public/' . $document->file_path;
    //             Storage::disk('public')->put($document->file_path, $fileContents);
    //             Log::info("Document saved: {$document->file_path}");

    //             // Storage::put($storagePath, $fileContents);

    //             Log::info("✅ Updated existing template: {$storagePath}");
    //         }

    //         // 🧠 CASE 2: Editing a temp upload before saving template
    //         elseif ($tempPath) {
    //             $storagePath = 'public/' . ltrim($tempPath, '/');
    //             Storage::put($storagePath, $fileContents);
    //             Log::info("✅ Updated temp file: {$storagePath}");
    //         }

    //     } catch (\Exception $e) {
    //         Log::error("❌ OnlyOffice callback failed: " . $e->getMessage());
    //         return response()->json(['error' => 1, 'message' => $e->getMessage()]);
    //     }

    //     return response()->json(['error' => 0]);
    // }
  
}