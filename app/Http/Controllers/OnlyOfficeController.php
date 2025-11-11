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
    public function callback(Request $request)
    {
        Log::info('OnlyOffice callback received', $request->all());

        $data = json_decode($request->getContent(), true);
        $status = $data['status'] ?? 0;
        $downloadUrl = $data['url'] ?? null;
        $documentId = $request->query('document_id');
        $tempPath = $request->query('tempPath');

        
        Log::info('OnlyOffice callback', ['status' => $status, 'documentId' => $documentId,'url' => $downloadUrl]);

        if (!in_array($status, [2, 6])) {
            return response()->json(['error' => 0]);
        }

        // $contents = file_get_contents($downloadUrl);

        if ($documentId && $downloadUrl) {
            $document = PatientDocument::findOrFail($documentId);

            // First-time save: create file path if empty
            if (!$document->file_path) {
                $extension = pathinfo($downloadUrl, PATHINFO_EXTENSION) ?: 'docx';
                $filePath = company_path('patient_docs/' . uniqid('patient_doc_') . '.' . $extension);
                
                $document->update(['file_path' => $filePath]);
            } else {
                $filePath = storage_path('app/public/' . $document->file_path);
            }

            file_put_contents($filePath, file_get_contents($downloadUrl));
            Log::info("Saved document to {$filePath}");
        } else {
            Log::error("No documentId provided to OnlyOffice callback");
        }
        // if ($documentId && $downloadUrl) {
        //     $document = PatientDocument::findOrFail($documentId);
        //     $filePath = storage_path('app/public/' . $document->file_path);
        //     try {
        //         file_put_contents($filePath, $contents);
        //         Log::info("Saved document to {$filePath}, size=" . strlen($contents));
        //     } catch (\Exception $e) {
        //         Log::error("Failed to save document: " . $e->getMessage());
        //         return response()->json(['error' => 1]);
        //     }
        // }
        // elseif ($tempPath) {
        //     Storage::disk('public')->put(ltrim($tempPath, '/'), $contents);
        //     Log::info("Updated temp file: {$tempPath}");
        // } else {
        //     throw new \Exception("No documentId or tempPath provided");
        // }

        return response()->json(['error' => 0]);
    }

    public function document_callback(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $status = $data['status'] ?? 0;
        $downloadUrl = $data['url'] ?? null;
        $documentId = $request->query('document_id');

        Log::info('OnlyOffice callback', ['status' => $status, 'url' => $downloadUrl, 'documentId' => $documentId]);

        if (!in_array($status, [2, 6])) return response()->json(['error' => 0]);

        if ($documentId && $downloadUrl) {
            $document = DocumentTemplate::findOrFail($documentId);

            // First-time save: create file path if empty
            if (!$document->file_path) {
                $extension = pathinfo($downloadUrl, PATHINFO_EXTENSION) ?: 'docx';
                $filePath = company_path('document_templates/' . uniqid('template_') . '.' . $extension);
                $document->update(['file_path' => $filePath]);
            } else {
                $filePath = storage_path('app/public/' . $document->file_path);
            }

            file_put_contents($filePath, file_get_contents($downloadUrl));
            Log::info("Saved document to {$filePath}");
        } else {
            Log::error("No documentId provided to OnlyOffice callback");
        }

        return response()->json(['error' => 0]);
    }

    // public function document_callback(Request $request)
    // {
    //     $data = json_decode($request->getContent(), true);
    //     $status = $data['status'] ?? 0;
    //     $downloadUrl = $data['url'] ?? null;
    //     $documentId = $request->query('document_id');
    //     $tempPath = $request->query('tempPath');
    //     Log::info('OnlyOffice tempPath', ['tempPath' => $tempPath]);

    //     Log::info('OnlyOffice callback', ['status' => $status, 'url' => $downloadUrl]);

    //     if (!in_array($status, [2, 6])) {
    //         return response()->json(['error' => 0]);
    //     }

    //     $contents = file_get_contents($downloadUrl);

    //     if ($documentId && $downloadUrl) {
    //         $document = DocumentTemplate::findOrFail($documentId);
    //         $filePath = storage_path('app/public/' . $document->file_path);
    //         try {
    //             file_put_contents($filePath, $contents);
    //             Log::info("Saved document to {$filePath}, size=" . strlen($contents));
    //         } catch (\Exception $e) {
    //             Log::error("Failed to save document: " . $e->getMessage());
    //             return response()->json(['error' => 1]);
    //         }
    //     }
    //     elseif ($tempPath) {
    //         Storage::disk('public')->put(ltrim($tempPath, '/'), $contents);
    //         Log::info("Updated temp file: {$tempPath}");
    //     } else {
    //         throw new \Exception("No documentId or tempPath provided");
    //     }
       

    //     return response()->json(['error' => 0]);
    // }

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