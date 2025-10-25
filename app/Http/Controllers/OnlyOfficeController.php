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

        $config = [
            'document' => [
                'fileType' => 'docx',
                'key' => generateDocumentKey($document),
                'title' => 'Document',
                'url' => $fileUrl,
            ],
            'documentType' => 'word',
            'editorConfig' => [
                'mode' => 'edit',
                // 'callbackUrl' => 'https://conceptmedicalpm.ie/onlyoffice/callback?document=' . $document->id,
                'callbackUrl' => route('onlyoffice.callback', ['document' => $document->id]),
                'user' => [
                    'id' => (string) auth()->user()?->id(),
                    'name' => auth()->user()?->name ?? "Guest",
                ],
                'customization' => [
                    'forcesave' => true,
                ],
            ]
            // 'token' =>  $token
        ];

        // $secret = env('ONLYOFFICE_JWT_SECRET');
        // $token = JWT::encode($config, $secret, 'HS256');
        // $config['token'] = $token;

        // $token = JWT::encode($config, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
        $config['token'] = $this->createJwtToken($document->id);//$token;
        
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
         // Generate JWT token using your secret from .env
        return JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
    }
}