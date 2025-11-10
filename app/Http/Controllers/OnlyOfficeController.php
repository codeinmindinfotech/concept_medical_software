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

    public function document_callback(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $status = $data['status'] ?? 0;
        $downloadUrl = $data['url'] ?? null;
        $documentId = $request->query('document_id');
        $tempPath = $request->query('tempPath');

        Log::info('OnlyOffice callback', ['status' => $status, 'url' => $downloadUrl]);

        if (!in_array($status, [2, 6])) {
            return response()->json(['error' => 0]);
        }

        $contents = file_get_contents($downloadUrl);

        if ($documentId && $downloadUrl) {
            $document = DocumentTemplate::findOrFail($documentId);
            $filePath = storage_path('app/public/' . $document->file_path);
            try {
                file_put_contents($filePath, $contents);
                Log::info("Saved document to {$filePath}, size=" . strlen($contents));
            } catch (\Exception $e) {
                Log::error("Failed to save document: " . $e->getMessage());
                return response()->json(['error' => 1]);
            }
        }
        elseif ($tempPath) {
            Storage::disk('public')->put(ltrim($tempPath, '/'), $contents);
            Log::info("Updated temp file: {$tempPath}");
        } else {
            throw new \Exception("No documentId or tempPath provided");
        }

        return response()->json(['error' => 0]);
    }


//     public function document_callback(Request $request, $documentId = null)
//     {
//        Log::info('OnlyOffice document Callback:', $request->all());

//        $data = json_decode($request->getContent(), true);
//        $status = $data['status'] ?? 0;
//        $downloadUri = $data['url'];
//        $tempPath = $request->query('tempPath');
//        $documentId = $documentId ?? $request->input('document_id');
       
//        Log::info("OnlyOffice callback | status: {$status}, documentId: {$documentId}, tempPath: {$tempPath}");

//        if (!in_array($status, [2, 4, 6])) {
//            Log::info("Ignoring status: {$status}");
//            return response()->json(['error' => 0]);
//        }

//         try {
//             Log::info("OnlyOffice downloadUri", ['url' => $downloadUri]);
//             if ($downloadUri) {
//                 $contents = file_get_contents($downloadUri);
//                 Log::info("Downloaded content size", ['size' => strlen($contents)]);
//             } else {
//                 Log::warning("Download URL empty, cannot save document", ['file' => $documentId]);
//             }
//         } catch (\Exception $e) {
//             Log::error("Error fetching content: " . $e->getMessage());
//             return response()->json(['error' => 'Content fetch failed'], 500);
//         }

//         if ($documentId) {
//             $document = DocumentTemplate::findOrFail($documentId);
//             if (!$document) {
//                 Log::error("Document not found: ID {$documentId}");
//                 return response()->json(['error' => 'Document not found'], 404);
//             }
//             $filePath = company_path($document->file_path); 
//             file_put_contents($filePath, $contents);

//             // Storage::disk('public')->put(ltrim($filePath, '/'), $contents);
//             Log::info("Updated existing template: {$document->file_path}");
//         } elseif ($tempPath) {
//             Storage::disk('public')->put(ltrim($tempPath, '/'), $contents);
//             Log::info("Updated temp file: {$tempPath}");
//         } else {
//             throw new \Exception("No documentId or tempPath provided");
//         }

//            Log::info("Saved document (size: " . strlen($contents) . " bytes)");
//        return response()->json(['error' => 0]);
//    }
 
   public function save(Request $request)
   {
       Log::info("save function call");
   
       $data = json_decode($request->getContent(), true);
       $documentId = $request->query('document_id');
       $status = $data['status'] ?? 0;
       $document = DocumentTemplate::findOrFail($documentId);
       $filePath = $document->file_path ?? $request->query('file'); // get ?file=... from URL

       Log::info("save function call", ['status' => $status, 'file' => $filePath]);
   
       if (in_array($status, [2, 6])) {
           $downloadUri = $data['url'];

           Log::info("OnlyOffice downloadUri", ['url' => $downloadUri]);
            if ($downloadUri) {
                
                $contents = file_get_contents($downloadUri);
                Log::info("Downloaded content size", ['size' => strlen($contents)]);
            } else {
                Log::warning("Download URL empty, cannot save document", ['file' => $filePath]);
            }

           $savePath = storage_path('app/public/' . $filePath);
   
           if ($downloadUri) {
            //    copy($downloadUri, $savePath);
               file_put_contents($savePath, $contents);

               Log::info("Copied updated file to: " . $savePath, ['size' => filesize($savePath)]);
           } else {
               Log::warning("Download URL empty, cannot save document", ['file' => $filePath]);
           }
       }
   
       return response()->json(['error' => 0]);
   }
   

}