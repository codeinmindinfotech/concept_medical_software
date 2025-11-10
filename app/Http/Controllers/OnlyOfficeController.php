<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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

    // public function document_callback(Request $request, $documentId = null)
    // {
    //     // Log incoming request for debugging
    //     Log::info('OnlyOffice Callback:', $request->all());

    //     $status = $request->input('status');
    //     $documentId = $documentId ?? $request->input('document_id');
    //     $tempPath = $request->input('temp_path');
    //     $url = $request->input('url'); // OnlyOffice may send a file URL

    //     // Only process statuses that indicate a save/finished document
    //     if (!in_array($status, [2, 4, 6])) {
    //         return response()->json(['error' => 'Status not processed'], 200);
    //     }

    //     // Fetch the updated content
    //     try {
    //         if ($url) {
    //             // Use Laravel HTTP client for better error handling
    //             $response = Http::get($url);
    //             if ($response->failed()) {
    //                 Log::error("Failed to fetch file from OnlyOffice URL: {$url}");
    //                 return response()->json(['error' => 'Failed to fetch file'], 500);
    //             }
    //             $content = $response->body();
    //         } else {
    //             $content = $request->getContent(); // Fallback to raw input
    //         }
    //     } catch (\Exception $e) {
    //         Log::error("Error fetching content: " . $e->getMessage());
    //         return response()->json(['error' => 'Content fetch failed'], 500);
    //     }

    //     // Save content to permanent document if document_id is provided
    //     if ($documentId) {
    //         $document = DocumentTemplate::find($documentId);
    //         if (!$document) {
    //             Log::error("Document not found: ID {$documentId}");
    //             return response()->json(['error' => 'Document not found'], 404);
    //         }

    //         // Save using Storage disk 'public' (path is relative to storage/app/public)
    //         Storage::disk('public')->put($document->file_path, $content);
    //         Log::info("Updated document ID {$documentId} at path: {$document->file_path}");
    //     }
    //     // Save content to temporary path if provided
    //     elseif ($tempPath) {
    //         Storage::disk('public')->put($tempPath, $content);
    //         Log::info("Updated temp file at path: {$tempPath}");
    //     } else {
    //         Log::warning('No document_id or temp_path provided in callback.');
    //         return response()->json(['error' => 'No path to save'], 400);
    //     }

    //     // OnlyOffice expects a JSON response
    //     return response()->json(['error' => 0], 200);
    // }

    public function document_callback(Request $request, $documentId = null)
    {
        Log::info('OnlyOffice Callback:', $request->all());

       $status = $request->input('status');
       $url = $request->input('url');
       $tempPath = $request->query('tempPath');
       $documentId = $documentId ?? $request->input('document_id');
       
       Log::info("OnlyOffice callback | status: {$status}, documentId: {$documentId}, tempPath: {$tempPath}");

       if (!in_array($status, [2, 4, 6])) {
           Log::info("Ignoring status: {$status}");
           return response()->json(['error' => 0]);
       }

       // Fetch the updated content
        try {
            if ($url) {
                // Use Laravel HTTP client for better error handling
                $response = Http::get($url);
                if ($response->failed()) {
                    Log::error("Failed to fetch file from OnlyOffice URL: {$url}");
                    return response()->json(['error' => 'Failed to fetch file'], 500);
                }
                $content = $response->body();
            } else {
                $content = $request->getContent(); // Fallback to raw input
            }
        } catch (\Exception $e) {
            Log::error("Error fetching content: " . $e->getMessage());
            return response()->json(['error' => 'Content fetch failed'], 500);
        }

    //    try {
            
    //        $content = $url ? file_get_contents($url) : file_get_contents('php://input');
    //        if (!$content) throw new \Exception("Empty file content");

           if ($documentId) {
               $document = DocumentTemplate::findOrFail($documentId);
               if (!$document) {
                    Log::error("Document not found: ID {$documentId}");
                    return response()->json(['error' => 'Document not found'], 404);
                }
               $filePath = company_path($document->file_path); 
               Storage::disk('public')->put(ltrim($filePath, '/'), $content);
            //   <!-- Storage::disk('public')->put(ltrim($document->file_path, '/'), $content); -->
               Log::info("Updated existing template: {$document->file_path}");
           } elseif ($tempPath) {
               Storage::disk('public')->put(ltrim($tempPath, '/'), $content);
               Log::info("Updated temp file: {$tempPath}");
           } else {
               throw new \Exception("No documentId or tempPath provided");
           }

           Log::info("Saved document (size: " . strlen($content) . " bytes)");

    //    } catch (\Exception $e) {
    //        Log::error("OnlyOffice callback failed: " . $e->getMessage());
    //        return response()->json(['error' => 1, 'message' => $e->getMessage()]);
    //    }

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
  
    public function save(Request $request)
    {
        Log::info("save function call ");
        $data = json_decode($request->getContent(), true);
        $status = $data['status'] ?? 0;
        $filePath = $request->query('file'); // get ?file=... from URL
        Log::info("save function call ",$status);
        if (in_array($status, [2, 6])) {
            $downloadUri = $data['url'];
            // $filePath = $template->file_path; // original file
            $savePath = storage_path('app/public/' . $filePath);
        
            if ($downloadUri) {
                copy($downloadUri, $savePath);
                Log::info("Copied updated file to: " . $savePath);
            } else {
                Log::warning("Download URL empty, cannot save document");
            }
        }
        // if (in_array($status, [2, 6])) {
        //     $downloadUri = $data['url'];

        //     try {
        //         $contents = file_get_contents($downloadUri);

        //         if ($filePath) {
        //             // Ensure folder exists
        //             $dir = dirname($filePath);
        //             Storage::disk('public')->makeDirectory($dir);

        //             // Save with same path and filename
        //             Storage::disk('public')->put($filePath, $contents);
        //             Log::info("✅ Saved updated file: {$filePath} (" . strlen($contents) . " bytes)");
        //         } else {
        //             Log::warning("⚠️ Missing file path in callback query.");
        //         }
        //     } catch (\Exception $e) {
        //         Log::error("❌ Error saving OnlyOffice file: " . $e->getMessage());
        //     }
        // }

        return response()->json(['error' => 0]);
    }

}