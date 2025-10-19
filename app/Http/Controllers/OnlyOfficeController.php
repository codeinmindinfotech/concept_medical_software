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
        $document = PatientDocument::findOrFail($documentId);
        $fileUrl  = asset('storage/' . $document->file_path);
        $key      = generateDocumentKey($document);  // you define this function

        // Build the full config payload
        $configPayload = [
            'document' => [
                'fileType' => 'docx',
                'key'      => $key,
                'title'    => $document->title ?? 'Document',
                'url'      => $fileUrl,
            ],
            'documentType' => 'word',
            'editorConfig' => [
                'mode'        => 'edit',
                'callbackUrl' => route('onlyoffice.callback', ['document' => $document->id]),
                'user'        => [
                    'id'   => (string) auth()->id(),
                    'name' => auth()->user()?->name ?? "Guest",
                ],
                'customization' => [
                    'forcesave' => true,
                ],
            ],
        ];

        $jwtSecret = env('ONLYOFFICE_JWT_SECRET');
        $token     = JWT::encode($configPayload, $jwtSecret, 'HS256');

        // Create the config for view
        $config = array_merge($configPayload, [
            'token' => $token,
        ]);

        return view('docs.editor', compact('config'));
    }

    public function callback(Request $request, $documentId = null)
    {
        \Log::info('OnlyOffice callback received', $request->all());

        $status = $request->get('status');
        if ($status == 2 || $status == 6) {
            $url     = $request->get('url');
            $newFile = file_get_contents($url);
            \Storage::disk('public')->put("documents/{$documentId}.docx", $newFile);
        }

        return response()->json(['error' => 0]);
    }
}
