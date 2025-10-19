<?php

namespace App\Http\Controllers;

use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;


class OnlyOfficeController extends Controller
{
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
                'key' => generateDocumentKey($document),
                'title' => 'Document',
                'url' => asset('storage/' . $document->file_path),
            ],
            'documentType' => 'word',
            'editorConfig' => [
                'mode' => 'edit',
                'callbackUrl' => route('onlyoffice.callback', ['document' => $document->id]),
                'user' => [
                    'id' => (string) auth()->id(),
                    'name' => auth()->user()?->name?? "Guest",
                ],
                'customization' => [
                    'forcesave' => true,
                ],
            ],
            "token" => $this->createJwtToken($documentId),
        ];
        // // 2. Sign the entire config with your secret to produce the JWT
        // $jwtSecret = env('ONLYOFFICE_JWT_SECRET', 'q4zQCWjKaflL0lC96Jdx'); // make sure it's set in .env
        // $token = JWT::encode($config, $jwtSecret, 'HS256');

        // // 3. Add the token to the config that will be passed to the frontend
        // $config['token'] = $token;
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