<?php

namespace App\Http\Controllers;

use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class OnlyOfficeController extends Controller
{
    public function editor($documentId)
    {
        $document = PatientDocument::where('id', $documentId)
            ->firstOrFail();
        $filePath = $document->file_path;
        $fileUrl = secure_asset('storage/' . $filePath);
        $token = $this->createJwtToken($document);

        $config = [
            'document' => [
                'storagePath' => storage_path('app/public'),
                'fileType' => 'docx',
                'key' => generateDocumentKey($document), // MUST be set
                'title' => $document->title ?? 'Document',
                'url' => $fileUrl, // full HTTPS URL
            ],
            'documentType' => 'word',
            'editorConfig' => [
                'mode' => 'edit',
                'callbackUrl' => route('onlyoffice.callback', ['fileId' => $document->id]),
                'user' => [
                    'id' => (string) auth()->user()?->id ?? '1',
                    'name' => auth()->user()?->name ?? 'Guest',
                ],
                'customization' => [
                    'forcesave' => true,
                ],
            ],
            'token' => $token, // your JWT token
        ];
        
        \Log::info('ONLYOFFICE TOKEN: ' . $token);

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

    private function createJwtToken($document)
    {
        $payload = [
            "userid" => auth()->id() ?? 1,
            "file" => generateDocumentKey($document),
            "iat" => time(),
            "exp" => time() + 3600,
        ];
         // Generate JWT token using your secret from .env
        return JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
    }
}