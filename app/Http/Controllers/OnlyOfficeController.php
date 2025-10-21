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
        // ->where('patient_id', $patient->id)
        ->firstOrFail();
        // Example: get file info from storage or DB
        $filePath = $document->file_path;
        $fileUrl = asset('storage/' . $filePath);
 // Create your payload – adjust fields as needed
//  $payload = [
//     "uid" => auth()->id(),  // user ID
//     "doc" => $document->id, // document identifier
//     "iat" => time(),        // issued at
//     "exp" => time() + 3600, // expiry (1 hour from now)
// ];

// // Encode the JWT token using the shared secret
// $token = JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
// Log::info($token);
// // Now attach it to your config
// $config['token'] = $token;
        // $config = [
        //     'document' => [
        //         'fileType' => 'docx',
        //         'key' => generateDocumentKey($document),
        //         'title' => 'Document',
        //         'url' => asset('storage/' . $document->file_path),
        //     ],
        //     'documentType' => 'word',
        //     'editorConfig' => [
        //         'mode' => 'edit',
        //         'callbackUrl' => route('onlyoffice.callback', ['document' => $document->id]),
        //         'user' => [
        //             'id' => (string) auth()->id(),
        //             'name' => auth()->user()?->name?? "Guest",
        //         ],
        //         'customization' => [
        //             'forcesave' => true,
        //         ],
        //     ]
        //     // "token" => $this->createJwtToken($document->id),
        // ];
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
                'callbackUrl' => route('onlyoffice.callback', ['document' => $document->id]),
                'user' => [
                    'id' => (string) auth()->id(),
                    'name' => auth()->user()?->name ?? "Guest",
                ],
                'customization' => [
                    'forcesave' => true,
                ],
            ]
            // 'token' =>  $token
        ];
        


       


        $token = JWT::encode($config, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
        $config['token'] = $token;
        
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